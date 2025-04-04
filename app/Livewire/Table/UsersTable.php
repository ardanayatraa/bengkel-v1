<?php

namespace App\Livewire\Table;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;

class UsersTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_user');
    }

    public function columns(): array
    {
        return [
            Column::make("Id user", "id_user")
                ->sortable(),
            Column::make("Id user", "id_user")
                ->sortable(),
            Column::make("Nama user", "nama_user")
                ->sortable(),
            Column::make("Level", "level")
                ->sortable(),
            Column::make("Username", "username")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
