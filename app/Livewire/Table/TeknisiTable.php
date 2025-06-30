<?php

namespace App\Livewire\Table;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Teknisi;

class TeknisiTable extends DataTableComponent
{
    protected $model = Teknisi::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_teknisi');
    }

    public function columns(): array
    {
        return [
            Column::make("ID Teknisi", "id_teknisi")
                ->sortable(),

            Column::make("Nama Teknisi", "nama_teknisi")
                ->sortable()
                ->searchable(),

            Column::make("Kontak", "kontak")
                ->sortable()
                ->searchable(),

            Column::make("Created At", "created_at")
                ->sortable(),

            Column::make("Updated At", "updated_at")
                ->sortable(),

            Column::make('Aksi')
                ->label(fn($row) => view('components.table-actions', [
                    'editRoute'   => route('teknisi.edit', $row->id_teknisi),
                    'deleteRoute' => route('teknisi.destroy', $row->id_teknisi),
                    'modalId'     => 'delete-teknisi-' . $row->id_teknisi,
                ])),
        ];
    }
}
