<?php

namespace App\Livewire\Table;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Jasa;

class JasaTable extends DataTableComponent
{
    protected $model = Jasa::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id jasa", "id_jasa")
                ->sortable(),
            Column::make("Nama jasa", "nama_jasa")
                ->sortable(),
            Column::make("Harga jasa", "harga_jasa")
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
