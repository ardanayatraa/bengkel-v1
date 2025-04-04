<?php

namespace App\Livewire\Table;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TrxBarangMasuk;

class TrxBarangMasukTable extends DataTableComponent
{
    protected $model = TrxBarangMasuk::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id trx barang masuk", "id_trx_barang_masuk")
                ->sortable(),
            Column::make("Id barang", "id_barang")
                ->sortable(),
            Column::make("Tanggal masuk", "tanggal_masuk")
                ->sortable(),
            Column::make("Nama supplier", "nama_supplier")
                ->sortable(),
            Column::make("Jumlah", "jumlah")
                ->sortable(),
            Column::make("Total harga", "total_harga")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
