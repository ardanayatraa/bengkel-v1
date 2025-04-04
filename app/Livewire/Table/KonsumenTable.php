<?php

namespace App\Livewire\Table;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Konsumen;

class KonsumenTable extends DataTableComponent
{
    protected $model = Konsumen::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id konsumen", "id_konsumen")
                ->sortable(),
            Column::make("Nama konsumen", "nama_konsumen")
                ->sortable(),
            Column::make("No kendaraan", "no_kendaraan")
                ->sortable(),
            Column::make("No telp", "no_telp")
                ->sortable(),
            Column::make("Alamat", "alamat")
                ->sortable(),
            Column::make("Jumlah point", "jumlah_point")
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
