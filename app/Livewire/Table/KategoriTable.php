<?php

namespace App\Livewire\Table;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Kategori;

class KategoriTable extends DataTableComponent
{
    protected $model = Kategori::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
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
        ];
    }
}
