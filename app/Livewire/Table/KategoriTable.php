<?php

namespace App\Livewire\Table;

use App\Models\Kategori;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class KategoriTable extends DataTableComponent
{
    protected $model = Kategori::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_kategori');
    }

    public function columns(): array
    {
        return [
            Column::make("Id kategori", "id_kategori")
                ->sortable(),
            Column::make("Nama kategori", "nama_kategori")
                ->sortable(),
            Column::make("Keterangan", "keterangan")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),

            Column::make('Aksi')->label(
                fn ($row) => view('components.table-actions', [
                    'editRoute' => route('kategori.edit', ['kategori' => $row->id_kategori]),
                    'deleteRoute' => route('kategori.destroy', ['kategori' => $row->id_kategori]),
                    'modalId' => 'delete-kategori-' . $row->id_kategori,
                ])
            ),
        ];
    }
}
