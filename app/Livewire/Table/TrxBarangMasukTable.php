<?php

namespace App\Livewire\Table;

use App\Models\TrxBarangMasuk;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TrxBarangMasukTable extends DataTableComponent
{
    protected $model = TrxBarangMasuk::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_trx_barang_masuk');
    }

    public function columns(): array
    {
        return [
            Column::make("Id trx barang masuk", "id_trx_barang_masuk")
                ->sortable(),
            Column::make("Id barang", "id_barang")
                ->sortable(),
            Column::make("Tanggal masuk", "tanggal_masuk")
                ->sortable(),
            Column::make("Jumlah", "jumlah")
                ->sortable(),
            Column::make("Total harga", "total_harga")
                ->sortable(),

            Column::make('Aksi')->label(
                fn ($row) => view('components.table-actions', [
                    'editRoute' => route('trx-barang-masuk.edit',$row->id_trx_barang_masuk),
                    'deleteRoute' => route('trx-barang-masuk.destroy',$row->id_trx_barang_masuk),
                    'modalId' => 'delete-trx-barang-masuk-' . $row->id_trx_barang_masuk,
                ])
            ),
        ];
    }
}
