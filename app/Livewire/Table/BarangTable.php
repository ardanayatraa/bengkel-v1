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
        return Barang::query()->with(['supplier']);
    }

    public function columns(): array
    {
        return [
            Column::make("No", "id_barang")
                ->format(function($value, $row, $column) {
                    static $counter = 0;
                    $counter++;
                    return $counter;
                }),

            // Tampilkan nama supplier, bukan id
            Column::make("Supplier", "supplier.nama_supplier")
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
