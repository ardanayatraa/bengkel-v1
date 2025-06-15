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

    // Eager load relasi untuk mencegah N+1 problem
    public function query()
    {
        return Barang::query()->with(['supplier', 'kategori']);
    }

    public function columns(): array
    {
        return [
            Column::make("Id Barang", "id_barang")->sortable(),

            // Tampilkan nama supplier, bukan id
            Column::make("Supplier", "supplier.nama_supplier")
                ->sortable(),

            // Tampilkan nama kategori, bukan id
            Column::make("Kategori", "kategori.nama_kategori")
                ->sortable(),

            Column::make("Nama Barang", "nama_barang")->sortable(),
            Column::make("Harga Beli", "harga_beli")->sortable(),
            Column::make("Harga Jual", "harga_jual")->sortable(),
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
