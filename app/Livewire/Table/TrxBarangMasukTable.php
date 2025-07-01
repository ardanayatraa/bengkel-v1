<?php

namespace App\Livewire\Table;

use App\Models\TrxBarangMasuk;
use App\Models\Barang;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;

class TrxBarangMasukTable extends DataTableComponent
{
    protected $model = TrxBarangMasuk::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_trx_barang_masuk');
    }

    /**
     * Definisi filters: filter per barang & rentang tanggal.
     */
    public function filters(): array
    {
        return [
            // Dropdown pilih Barang
            SelectFilter::make('Barang')
                ->options(
                    // ambil daftar barang: [id => nama]
                    Barang::orderBy('nama_barang')
                          ->pluck('nama_barang', 'id_barang')
                          ->toArray()
                )
                ->filter(function($query, $value) {
                    if ($value) {
                        $query->where('id_barang', $value);
                    }
                }),

            // Tanggal Mulai (>=)
            DateFilter::make('Dari')
                ->filter(function($query, $value) {
                    $query->whereDate('tanggal_masuk', '>=', $value);
                }),

            // Tanggal Akhir (<=)
            DateFilter::make('Sampai')
                ->filter(function($query, $value) {
                    $query->whereDate('tanggal_masuk', '<=', $value);
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Id trx barang masuk", "id_trx_barang_masuk")
                ->sortable()
                ->format(
                    fn($value) =>
                        '<a href="'.route('trx-barang-masuk.show', $value).'" class="text-blue-600 hover:underline">'.$value.'</a>'
                )
                ->html(),

            Column::make("Barang", "barang.nama_barang")
                ->sortable(),

            Column::make("Supplier", "barang.supplier.nama_supplier")
                ->sortable(),

            Column::make("Tanggal masuk", "tanggal_masuk")
                ->sortable(),

            Column::make("Jumlah", "jumlah")
                ->sortable(),

            Column::make("Total harga", "total_harga")
                ->sortable(),

            Column::make('Aksi')->label(
                fn ($row) => view('components.table-actions', [
                    'editRoute'   => route('trx-barang-masuk.edit', $row->id_trx_barang_masuk),
                    'deleteRoute' => route('trx-barang-masuk.destroy', $row->id_trx_barang_masuk),
                    'modalId'     => 'delete-trx-barang-masuk-' . $row->id_trx_barang_masuk,
                ])
            ),
        ];
    }
}
