<?php

namespace App\Livewire\Table;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UsersTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_user');
    }

    public function builder() : \Illuminate\Database\Eloquent\Builder
    {
        return User::query()
            ->where('id_user', '!=', Auth::id());
    }



    public function columns(): array
    {
        return [
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

            Column::make('Aksi')->label(
                fn ($row) => view('components.table-actions', [
                    'editRoute' => route('user.edit', ['user' => $row->id_user]),
                    'deleteRoute' => route('user.destroy', ['user' => $row->id_user]),
                    'modalId' => 'delete-user-' . $row->id_user,
                ])
            ),
        ];
    }
}
