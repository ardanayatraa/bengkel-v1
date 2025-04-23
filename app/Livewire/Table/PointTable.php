<?php

namespace App\Livewire\Table;

use App\Models\Point;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PointTable extends DataTableComponent
{
    protected $model = Point::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_point');
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

            Column::make('Aksi')->label(
                fn ($row) => view('components.table-actions', [
                    'editRoute' => route('point.edit', ['point' => $row->id_point]),
                    'deleteRoute' => route('point.destroy', ['point' => $row->id_point]),
                    'modalId' => 'delete-point-' . $row->id_point,
                ])
            ),
        ];
    }
}
