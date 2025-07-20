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
     * Definisi filter: per Barang & rentang tanggal masuk.
     */
    public function filters(): array
    {
        // ambil nama tabel model agar bisa diprefix di where
        $table = (new TrxBarangMasuk)->getTable(); // misal 'trx_barang_masuks'

        return [
            SelectFilter::make('Barang')
                ->options(
                    Barang::orderBy('nama_barang')
                          ->pluck('nama_barang', 'id_barang')
                          ->toArray()
                )
                ->filter(function($query, $value) use ($table) {
                    if ($value) {
                        // prefix dengan nama tabel untuk menghindari ambigu
                        $query->where("{$table}.id_barang", $value);
                    }
                }),

            DateFilter::make('Dari')
                ->filter(function($query, $value) use ($table) {
                    $query->whereDate("{$table}.tanggal_masuk", '>=', $value);
                }),

            DateFilter::make('Sampai')
                ->filter(function($query, $value) use ($table) {
                    $query->whereDate("{$table}.tanggal_masuk", '<=', $value);
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("No", "id_trx_barang_masuk")
                ->format(function($value, $row, $column) {
                    static $counter = 0;
                    $counter++;
                    return $counter;
                }),

            Column::make("Barang", "barang.nama_barang")
                ->sortable(),

            Column::make("Supplier", "barang.supplier.nama_supplier")
                ->sortable(),

            Column::make("Tanggal Masuk", "tanggal_masuk")
                ->sortable(),

            Column::make("Jumlah", "jumlah")
                ->sortable(),

            Column::make("Total Harga", "total_harga")
                ->sortable(),

            Column::make('Aksi')
                ->label(fn($row) =>
                    view('components.table-actions', [
                        'editRoute'   => route('trx-barang-masuk.edit',   $row->id_trx_barang_masuk),
                        'deleteRoute' => route('trx-barang-masuk.destroy',$row->id_trx_barang_masuk),
                        'modalId'     => 'delete-trx-barang-masuk-'.$row->id_trx_barang_masuk,
                    ])
                ),
        ];
    }
}
