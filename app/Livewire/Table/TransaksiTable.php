<?php

namespace App\Livewire\Table;

use App\Models\Transaksi;
use App\Models\Konsumen;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;

class TransaksiTable extends DataTableComponent
{
    protected $model = Transaksi::class;

    public function builder(): Builder
    {
        return Transaksi::query()
            ->select('transaksis.*')
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id_transaksi');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status Service')
                ->options([
                    ''        => 'Semua',
                    'proses'  => 'Proses',
                    'selesai' => 'Selesai',
                    'diambil' => 'Diambil',
                ])
                ->filter(fn($query, $value) =>
                    $value !== '' ? $query->where('status_service', $value) : $query
                ),

            SelectFilter::make('Status Bayar')
                ->options([
                    ''           => 'Semua',
                    'belum bayar'=> 'Belum Bayar',
                    'lunas'      => 'Lunas',
                ])
                ->filter(fn($query, $value) =>
                    $value !== '' ? $query->where('status_pembayaran', $value) : $query
                ),

            DateFilter::make('Dari')
                ->filter(fn($query, $value) =>
                    $query->whereDate('tanggal_transaksi', '>=', $value)
                ),

            DateFilter::make('Sampai')
                ->filter(fn($query, $value) =>
                    $query->whereDate('tanggal_transaksi', '<=', $value)
                ),
        ];
    }

    public function columns(): array
    {
        $columns = [
            Column::make('ID Transaksi', 'id_transaksi')
                ->sortable()
                ->searchable(),

            Column::make('Nama Konsumen', 'konsumen.nama_konsumen')
                ->sortable(fn($q, $d) =>
                    $q->join('konsumens', 'transaksis.id_konsumen', '=', 'konsumens.id_konsumen')
                    ->orderBy('konsumens.nama_konsumen', $d)
                )
                ->searchable(),

            Column::make('Kasir', 'kasir.nama_user')
                ->sortable()
                ->searchable(),

            Column::make('Tanggal Transaksi', 'tanggal_transaksi')
                ->sortable()
                ->searchable(),

            Column::make('Total Harga', 'total_harga')
                ->sortable()
                ->format(fn($v) => 'Rp ' . number_format($v, 0, ',', '.')),

            Column::make('Metode Pembayaran', 'metode_pembayaran')
                ->sortable()
                ->searchable(),

            // Status Pembayaran dengan component bayar-action
            Column::make('Pembayaran')
                ->label(fn($row) => view('components.bayar-action', [
                    'transaksi' => $row,
                    'modalId' => 'bayar-transaksi-' . $row->id_transaksi,
                ]))
                ->html(),

            // Inline select untuk Status Service
            Column::make('Status Service')
                ->html()
                ->format(function ($_, $row) {
                    $raw = $row->id_jasa;
                    $ids = is_array($raw) ? $raw : (json_decode($raw, true) ?: []);
                    $hasJasa = \Illuminate\Support\Arr::isList($ids) && count($ids) > 0;
                    if (!$hasJasa) {
                        return '<span class="text-gray-400">–</span>';
                    }
                    $options = ['proses', 'selesai', 'diambil'];
                    $html = '<select wire:change="updateStatus(' . $row->id_transaksi . ', $event.target.value)" class="border rounded px-2 py-1 bg-white">';
                    foreach ($options as $opt) {
                        $sel = $row->status_service === $opt ? ' selected' : '';
                        $html .= "<option value=\"{$opt}\"{$sel}>" . ucfirst($opt) . "</option>";
                    }
                    return $html . '</select>';
                }),

            Column::make('Estimasi Pengerjaan', 'estimasi_pengerjaan')
                ->sortable()
                ->searchable(),

            Column::make('Teknisi', 'teknisi.nama_teknisi')
                ->sortable(fn($q, $d) =>
                    $q->leftJoin('teknisis', 'transaksis.id_teknisi', '=', 'teknisis.id_teknisi')
                    ->orderBy('teknisis.nama_teknisi', $d)
                )
                ->searchable(),

            LinkColumn::make('Detail')
                ->title(fn($row) => 'Detail')
                ->location(fn($row) => route('transaksi.show', $row->id_transaksi)),
        ];

        // Tambahkan kolom Aksi hanya jika user adalah admin
        if (Auth::user()->level === 'admin') {
            $columns[] = Column::make('Aksi')
                ->label(fn($row) => view('components.table-action-admin-trx', [
                    'deleteRoute' => route('transaksi.destroy', $row->id_transaksi),
                    'modalId'     => 'delete-transaksi-' . $row->id_transaksi,
                ]))
                ->html();
        }

        return $columns;
    }

    /**
     * Update status_service existing method...
     */
    public function updateStatus(int $id, string $newStatus): void
    {
        $t = Transaksi::find($id);
        if ($t && in_array($newStatus, ['proses','selesai','diambil'], true)) {
            $t->status_service = $newStatus;
            $t->save();
            if ($newStatus === 'diambil') {
                $k = Konsumen::find($t->id_konsumen);
                $k->increment('jumlah_point', 1);
            }
        }
        $this->dispatch('refreshDatatable');
    }

    /**
     * Update status_pembayaran (kept for backward compatibility)
     */
    public function updatePaymentStatus(int $id, string $newStatus): void
    {
        $t = Transaksi::find($id);
        if ($t && in_array($newStatus, ['belum bayar','lunas'], true)) {
            $t->status_pembayaran = $newStatus;
            $t->save();
        }
        $this->dispatch('refreshDatatable');
    }

    /**
     * Method untuk proses pembayaran (dipanggil dari component bayar-action)
     */
    public function processPayment(int $id, float $uangDiterima, float $kembalian): void
    {
        $transaksi = Transaksi::find($id);

        if ($transaksi && $transaksi->status_pembayaran === 'belum bayar') {
            // Validasi uang diterima tidak boleh kurang dari total harga
            if ($uangDiterima < $transaksi->total_harga) {
                session()->flash('error', 'Uang yang diterima kurang dari total harga');
                return;
            }

            $transaksi->update([
                'status_pembayaran' => 'lunas',
                'uang_diterima' => $uangDiterima,
                'kembalian' => $kembalian,
            ]);

            session()->flash('success', 'Pembayaran berhasil diproses');
        }

        $this->dispatch('refreshDatatable');
    }
}
