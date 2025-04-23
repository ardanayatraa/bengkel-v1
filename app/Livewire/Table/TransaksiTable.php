<?php

namespace App\Livewire\Table;

use App\Models\Transaksi;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TransaksiTable extends DataTableComponent
{
    protected $model = Transaksi::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_transaksi');
    }

    public function columns(): array
    {
        return [
            Column::make("ID Transaksi", "id_transaksi")
                ->sortable()
                ->searchable(),

            Column::make("Nama Konsumen", "konsumen.nama_konsumen")
                ->sortable(function ($query, $direction) {
                    $query->join('konsumens', 'transaksis.id_konsumen', '=', 'konsumens.id_konsumen')
                          ->orderBy('konsumens.nama_konsumen', $direction);
                })
                ->searchable(),

            Column::make("Tanggal Transaksi", "tanggal_transaksi")
                ->sortable()
                ->searchable(),

            Column::make("Total Harga", "total_harga")
                ->sortable()
                ->format(fn($value) => 'Rp ' . number_format($value, 0, ',', '.')),

            Column::make("Metode Pembayaran", "metode_pembayaran")
                ->sortable()
                ->searchable(),


        ];
    }
}
