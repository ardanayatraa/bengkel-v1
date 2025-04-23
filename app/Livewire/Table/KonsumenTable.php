<?php

namespace App\Livewire\Table;

use App\Models\Konsumen;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class KonsumenTable extends DataTableComponent
{
    protected $model = Konsumen::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_konsumen');
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

            Column::make('Member')->label(
                function ($row) {
                    if (strtolower($row->keterangan) === 'member') {
                        return '<a href="' . route('konsumen.cetak-kartu', ['konsumen' => $row->id_konsumen]) . '"
                                    class="px-2 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700"
                                    target="_blank">
                                    Cetak
                                </a>';
                    } else {
                        return '<button disabled
                                    class="px-2 py-1 bg-gray-300 text-gray-500 rounded text-xs cursor-not-allowed"
                                    title="Hanya untuk member">
                                    Cetak
                                </button>';
                    }
                }
            )->html(),

            Column::make('Aksi')->label(
                fn ($row) => view('components.table-actions', [
                    'editRoute' => route('konsumen.edit', ['konsumen' => $row->id_konsumen]),
                    'deleteRoute' => route('konsumen.destroy', ['konsumen' => $row->id_konsumen]),
                    'modalId' => 'delete-konsumen-' . $row->id_konsumen,
                ])
            ),


        ];
    }
}
