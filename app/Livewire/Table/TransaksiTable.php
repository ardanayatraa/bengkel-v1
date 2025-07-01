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
        }
        $k=Konsumen::find($t->id_konsumen);
        // If the status is 'diambil', increment konsumen's jumlah_point
        if ($newStatus === 'diambil') {

            $k->jumlah_point->jumlah_point += 1;
            $k->jumlah_point->save();

        }
        // refresh the table data
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

            // Inline-editable Status Service with Livewire action
            Column::make('Status Service')
                ->html()
                ->format(fn($_, $row) =>
                    "<select
                        wire:change=\"updateStatus({$row->id_transaksi}, \$event.target.value)\"
                        class=\"border rounded px-4 py-1 px-2 bg-white\">
                        <option value=\"proses\" " . ($row->status_service === 'proses' ? 'selected' : '') . ">Proses</option>
                        <option value=\"selesai\" " . ($row->status_service === 'selesai' ? 'selected' : '') . ">Selesai</option>
                        <option value=\"diambil\" " . ($row->status_service === 'diambil' ? 'selected' : '') . ">Diambil</option>
                     </select>"
                ),

            Column::make('Estimasi Pengerjaan', 'estimasi_pengerjaan')
                ->sortable()
                ->searchable(),

            Column::make('Teknisi', 'teknisi.nama_teknisi')
                ->sortable(fn($query, $direction) =>
                    $query->leftJoin('teknisis', 'transaksis.id_teknisi', '=', 'teknisis.id_teknisi')
                          ->orderBy('teknisis.nama_teknisi', $direction)
                )
                ->searchable(),

                LinkColumn::make('Action')
        ->title(fn($row) => 'Detail')
        ->location(fn($row) => route('transaksi.show', $row->id_transaksi)),
            ];
    }
}
