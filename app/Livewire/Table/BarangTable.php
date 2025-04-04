<?php

namespace App\Livewire\Table;

use App\Models\Barang;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BarangTable extends DataTableComponent
{
    protected $model = Barang::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_barang');
    }

    public function columns(): array
    {
        return [
            Column::make("Id barang", "id_barang")->sortable(),
            Column::make("Id supplier", "id_supplier")->sortable(),
            Column::make("Id kategori", "id_kategori")->sortable(),
            Column::make("Nama barang", "nama_barang")->sortable(),
            Column::make("Harga beli", "harga_beli")->sortable(),
            Column::make("Harga jual", "harga_jual")->sortable(),
            Column::make("Stok", "stok")->sortable(),
            Column::make("Keterangan", "keterangan")->sortable(),

            Column::make('Aksi')->label(
                fn ($row) => view('components.table-actions', [
                    'editRoute' => route('barang.edit', $row->id_barang),
                    'deleteRoute' => route('barang.destroy', $row->id_barang),
                    'modalId' => 'delete-barang-' . $row->id_barang,
                ])
            ),

        ];
    }
}
