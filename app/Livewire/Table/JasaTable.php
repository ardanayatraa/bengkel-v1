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
        $this->setPrimaryKey('id_jasa');
    }

    public function columns(): array
    {
        return [
            Column::make("No", "id_jasa")
                ->format(function($value, $row, $column) {
                    static $counter = 0;
                    $counter++;
                    return $counter;
                }),
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

                Column::make('Aksi')->label(
                    fn ($row) => view('components.table-actions', [
                        'editRoute' => route('jasa.edit', $row->id_jasa),
                        'deleteRoute' => route('jasa.destroy', $row->id_jasa),
                        'modalId' => 'delete-jasa-' . $row->id_jasa,
                    ])
                ),


        ];
    }
}
