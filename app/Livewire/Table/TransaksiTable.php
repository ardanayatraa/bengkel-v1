<?php

namespace App\Livewire\Table;

use App\Models\Transaksi;
use App\Models\Konsumen;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;

class TransaksiTable extends DataTableComponent
{
    protected $model = Transaksi::class;

    /**
     * Override builder supaya kita seleksi semua kolom transaksis.*
     * Termasuk id_jasa & id_barang.
     */
    public function builder(): Builder
    {
        return Transaksi::query()->select('transaksis.*');
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
        return [
            Column::make('ID Transaksi', 'id_transaksi')
                ->sortable()
                ->searchable(),

            Column::make('Nama Konsumen', 'konsumen.nama_konsumen')
                ->sortable(fn($query, $dir) =>
                    $query->join('konsumens', 'transaksis.id_konsumen', '=', 'konsumens.id_konsumen')
                          ->orderBy('konsumens.nama_konsumen', $dir)
                )
                ->searchable(),

            Column::make('Tanggal Transaksi', 'tanggal_transaksi')
                ->sortable()
                ->searchable(),

            Column::make('Total Harga', 'total_harga')
                ->sortable()
                ->format(fn($value) => 'Rp ' . number_format($value, 0, ',', '.')),

            Column::make('Metode Pembayaran', 'metode_pembayaran')
                ->sortable()
                ->searchable(),

            Column::make('Status Service')
                ->html()
                ->format(function($_, $row) {
                    // ambil id_jasa -> array
                    $raw = $row->id_jasa;
                    $ids = is_array($raw) ? $raw : (json_decode($raw, true) ?: []);

                    // cek list numerik => transaksi jasa
                    $hasJasa = Arr::isList($ids) && count($ids) > 0;

                    if ($hasJasa) {
                        $options = ['proses','selesai','diambil'];
                        $html = '<select wire:change="updateStatus('.$row->id_transaksi.', $event.target.value)" class="border rounded px-2 py-1 bg-white">';
                        foreach ($options as $opt) {
                            $sel   = $row->status_service === $opt ? ' selected' : '';
                            $label = ucfirst($opt);
                            $html .= "<option value=\"{$opt}\"{$sel}>{$label}</option>";
                        }
                        return $html.'</select>';
                    }

                    return '<span class="text-gray-400">–</span>';
                }),

            Column::make('Estimasi Pengerjaan', 'estimasi_pengerjaan')
                ->sortable()
                ->searchable(),

            Column::make('Teknisi', 'teknisi.nama_teknisi')
                ->sortable(fn($query, $dir) =>
                    $query->leftJoin('teknisis', 'transaksis.id_teknisi', '=', 'teknisis.id_teknisi')
                          ->orderBy('teknisis.nama_teknisi', $dir)
                )
                ->searchable(),

            LinkColumn::make('Action')
                ->title(fn($row) => 'Detail')
                ->location(fn($row) => route('transaksi.show', $row->id_transaksi)),
        ];
    }

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
}
