<?php

namespace App\Livewire\Table;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Point;

class PointTable extends DataTableComponent
{
    protected $model = Point::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id point", "id_point")
                ->sortable(),
            Column::make("Id konsumen", "id_konsumen")
                ->sortable(),
            Column::make("Tanggal", "tanggal")
                ->sortable(),
            Column::make("Jumlah point", "jumlah_point")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
