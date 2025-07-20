<?php

namespace App\Livewire\Table;

use App\Models\GajiTeknisi;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;

class GajiTeknisiTable extends DataTableComponent
{
    protected $model = GajiTeknisi::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_gaji_teknisi');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status Pembayaran')
                ->options([
                    '' => 'Semua',
                    'belum_dibayar' => 'Belum Dibayar',
                    'sudah_dibayar' => 'Sudah Dibayar',
                ])
                ->filter(fn($query, $value) =>
                    $value !== '' ? $query->where('status_pembayaran', $value) : $query
                ),

            DateFilter::make('Dari')
                ->filter(fn($query, $value) =>
                    $query->whereDate('tanggal_kerja', '>=', $value)
                ),

            DateFilter::make('Sampai')
                ->filter(fn($query, $value) =>
                    $query->whereDate('tanggal_kerja', '<=', $value)
                ),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("No", "id_gaji_teknisi")
                ->format(function($value, $row, $column) {
                    static $counter = 0;
                    $counter++;
                    return $counter;
                }),

            Column::make("Teknisi", "teknisi.nama_teknisi")
                ->sortable()
                ->searchable(),

            Column::make("Jasa", "jasa.nama_jasa")
                ->sortable()
                ->searchable(),

            Column::make("Tanggal Kerja", "tanggal_kerja")
                ->sortable()
                ->format(fn($value) => $value ? date('d/m/Y', strtotime($value)) : '-'),

            Column::make("Jumlah Gaji", "jumlah_gaji")
                ->sortable()
                ->format(fn($value) => 'Rp ' . number_format($value, 0, ',', '.')),

            Column::make("Status Pembayaran", "status_pembayaran")
                ->sortable()
                ->format(function($value) {
                    $badgeClass = $value === 'sudah_dibayar' 
                        ? 'bg-green-100 text-green-800' 
                        : 'bg-yellow-100 text-yellow-800';
                    
                    $text = $value === 'sudah_dibayar' ? 'Sudah Dibayar' : 'Belum Dibayar';
                    
                    return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $badgeClass . '">'
                           . $text . '</span>';
                })
                ->html(),

            Column::make('Aksi')
                ->label(fn($row) => view('components.table-actions', [
                    'editRoute' => route('gaji-teknisi.edit', $row->id_gaji_teknisi),
                    'deleteRoute' => route('gaji-teknisi.destroy', $row->id_gaji_teknisi),
                    'modalId' => 'delete-gaji-teknisi-' . $row->id_gaji_teknisi,
                ])),
        ];
    }
} 