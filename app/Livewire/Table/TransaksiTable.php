<?php

namespace App\Livewire\Table;

use App\Models\Transaksi;
use App\Models\Konsumen;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;

class TransaksiTable extends DataTableComponent
{
    protected $model = Transaksi::class;

    public function builder(): Builder
    {
        return Transaksi::query()->select('transaksis.*');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id_transaksi');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status Service')
                ->options([
                    ''        => 'Semua',
                    'proses'  => 'Proses',
                    'selesai' => 'Selesai',
                    'diambil' => 'Diambil',
                ])
                ->filter(fn($query, $value) =>
                    $value !== '' ? $query->where('status_service', $value) : $query
                ),

            SelectFilter::make('Status Bayar')
                ->options([
                    ''           => 'Semua',
                    'belum bayar'=> 'Belum Bayar',
                    'lunas'      => 'Lunas',
                ])
                ->filter(fn($query, $value) =>
                    $value !== '' ? $query->where('status_pembayaran', $value) : $query
                ),

            DateFilter::make('Dari')
                ->filter(fn($query, $value) =>
                    $query->whereDate('tanggal_transaksi', '>=', $value)
                ),

            DateFilter::make('Sampai')
                ->filter(fn($query, $value) =>
                    $query->whereDate('tanggal_transaksi', '<=', $value)
                ),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('ID Transaksi', 'id_transaksi')
                ->sortable()
                ->searchable(),

            Column::make('Nama Konsumen', 'konsumen.nama_konsumen')
                ->sortable(fn($q,$d) =>
                    $q->join('konsumens','transaksis.id_konsumen','=','konsumens.id_konsumen')
                      ->orderBy('konsumens.nama_konsumen',$d)
                )
                ->searchable(),

            Column::make('Kasir','kasir.nama_user')
                ->sortable()
                ->searchable(),
            Column::make('Tanggal Transaksi','tanggal_transaksi')
                ->sortable()
                ->searchable(),

            Column::make('Total Harga','total_harga')
                ->sortable()
                ->format(fn($v)=>'Rp '.number_format($v,0,',','.')),

            Column::make('Metode Pembayaran','metode_pembayaran')
                ->sortable()
                ->searchable(),

            // === NEW: Status Pembayaran INLINE ===
           Column::make('Status Pembayaran')
    ->html()
    ->format(function($_, $row) {
        $options = ['belum bayar','lunas'];
        $html = '<select wire:change="updatePaymentStatus('
              . $row->id_transaksi
              . ', $event.target.value)" class="border rounded px-2 py-1 bg-white">';
        foreach ($options as $opt) {
            $sel = $row->status_pembayaran === $opt ? ' selected' : '';
            $html .= "<option value=\"{$opt}\"{$sel}>"
                   . ucfirst($opt)
                   . "</option>";
        }
        return $html.'</select>';
    }),


            // EXISTING: Status Service
            Column::make('Status Service')
                ->html()
                ->format(function($_,$row){
                    $raw = $row->id_jasa;
                    $ids = is_array($raw) ? $raw : (json_decode($raw,true)?:[]);
                    $hasJasa = Arr::isList($ids) && count($ids)>0;
                    if(!$hasJasa) {
                        return '<span class="text-gray-400">–</span>';
                    }
                    $options = ['proses','selesai','diambil'];
                    $html = '<select wire:change="updateStatus('.$row->id_transaksi.', $event.target.value)" '
                          . 'class="border rounded px-2 py-1 bg-white">';
                    foreach($options as $opt){
                        $sel   = $row->status_service === $opt ? ' selected' : '';
                        $lbl   = ucfirst($opt);
                        $html .= "<option value=\"{$opt}\"{$sel}>{$lbl}</option>";
                    }
                    return $html.'</select>';
                }),

            Column::make('Estimasi Pengerjaan','estimasi_pengerjaan')
                ->sortable()
                ->searchable(),

            Column::make('Teknisi','teknisi.nama_teknisi')
                ->sortable(fn($q,$d)=>
                    $q->leftJoin('teknisis','transaksis.id_teknisi','=','teknisis.id_teknisi')
                      ->orderBy('teknisis.nama_teknisi',$d)
                )
                ->searchable(),

            LinkColumn::make('Detail')
                ->title(fn($row) => 'Detail')
                ->location(fn($row)=>route('transaksi.show',$row->id_transaksi)),

             Column::make('Aksi')
                    ->label(fn($row) => view('components.table-action-admin-trx', [

                'deleteRoute' => Auth::user()->level === 'admin'
                    ? route('transaksi.destroy', $row->id_transaksi)
                    : null,
                'modalId'     => 'delete-transaksi-' . $row->id_transaksi,
            ]))
            ->html(),
        ];
    }

    /**
     * Update status_service existing method...
     */
    public function updateStatus(int $id, string $newStatus): void
    {
        $t = Transaksi::find($id);
        if ($t && in_array($newStatus, ['proses','selesai','diambil'], true)) {
            $t->status_service = $newStatus;
            $t->save();
            if ($newStatus === 'diambil') {
                $k = Konsumen::find($t->id_konsumen);
                $k->increment('jumlah_point', 1);

            }
        }
        $this->dispatch('refreshDatatable');
    }

    /**
     * NEW: Update status_pembayaran
     */
    public function updatePaymentStatus(int $id, string $newStatus): void
    {
        $t = Transaksi::find($id);
        if ($t && in_array($newStatus, ['belum bayar','lunas'], true)) {
            $t->status_pembayaran = $newStatus;
            $t->save();
        }
        $this->dispatch('refreshDatatable');
    }
}
