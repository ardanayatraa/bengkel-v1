<?php

namespace App\Livewire\Table;

use App\Models\Konsumen;
use App\Models\Transaksi;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class TransaksiTable extends DataTableComponent
{
    protected $model = Transaksi::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_transaksi');
    }

    /**
     * Livewire action to update status_service on the fly.
     */
    public function updateStatus(int $id, string $newStatus): void
    {
        $t = Transaksi::find($id);
        if ($t && in_array($newStatus, ['proses','selesai','diambil'], true)) {
            $t->status_service = $newStatus;
            $t->save();

            // Jika berpindah ke 'diambil', beri 1 poin ke konsumen
            if ($newStatus === 'diambil') {
                $k = Konsumen::find($t->id_konsumen);
                $k->increment('jumlah_point', 1);
            }
        }

        // refresh table
        $this->dispatch('refreshDatatable');
    }

    public function columns(): array
    {
        return [
            Column::make('ID Transaksi', 'id_transaksi')
                ->sortable()
                ->searchable(),

            Column::make('Nama Konsumen', 'konsumen.nama_konsumen')
                ->sortable(fn($query, $direction) =>
                    $query->join('konsumens', 'transaksis.id_konsumen', '=', 'konsumens.id_konsumen')
                          ->orderBy('konsumens.nama_konsumen', $direction)
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

            // Hanya tampilkan dropdown jika ada jasa, jika tidak tampilkan '-'
           // di dalam columns(): array
                Column::make('Status Service')
                    ->html()
                    ->format(function($_, Transaksi $row) {
                        // hitung jasa via relasi model
                        $hasJasa = $row->jasaModels()->isNotEmpty();

                        if (! $hasJasa) {
                            return '<span class="text-gray-500">–</span>';
                        }

                        $opts = [
                            'proses'  => 'Proses',
                            'selesai' => 'Selesai',
                            'diambil' => 'Diambil',
                        ];

                        $html  = '<select wire:change="updateStatus('.$row->id_transaksi.', $event.target.value)"';
                        $html .= ' class="border rounded px-2 py-1 bg-white">';
                        foreach ($opts as $value => $label) {
                            $sel = $row->status_service === $value ? ' selected' : '';
                            $html .= "<option value=\"{$value}\"{$sel}>{$label}</option>";
                        }
                        $html .= '</select>';

                        return $html;
                    }),



            Column::make('Estimasi Pengerjaan', 'estimasi_pengerjaan')
                ->sortable()
                ->searchable(),

            Column::make('Teknisi', 'teknisi.nama_teknisi')
                ->sortable(fn($query, $direction) =>
                    $query->leftJoin('teknisis', 'transaksis.id_teknisi', '=', 'teknisis.id_teknisi')
                          ->orderBy('teknisis.nama_teknisi', $direction)
                )
                ->searchable(),

            LinkColumn::make('Aksi')
                ->title(fn($row) => 'Detail')
                ->location(fn($row) => route('transaksi.show', $row->id_transaksi)),
        ];
    }
}
