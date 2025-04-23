<?php

namespace App\Livewire\Table;

use App\Models\Supplier;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SupplierTable extends DataTableComponent
{
    protected $model = Supplier::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_supplier');
    }

    public function columns(): array
    {
        return [
            Column::make("Id supplier", "id_supplier")
                ->sortable(),
            Column::make("Nama supplier", "nama_supplier")
                ->sortable(),
            Column::make("No telp", "no_telp")
                ->sortable(),
            Column::make("Alamat", "alamat")
                ->sortable(),
            Column::make('Aksi')->label(
                fn ($row) => view('components.table-actions', [
                    'editRoute' => route('supplier.edit', ['supplier' => $row->id_supplier]),
                    'deleteRoute' => route('supplier.destroy', ['supplier' => $row->id_supplier]),
                    'modalId' => 'delete-supplier-' . $row->id_supplier,
                ])
            ),
        ];
    }
}
