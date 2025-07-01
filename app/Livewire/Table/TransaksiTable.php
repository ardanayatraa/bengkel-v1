<?php

namespace App\Livewire\Table;

use App\Models\Konsumen;
use App\Models\Transaksi;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class TransaksiTable extends DataTableComponent
{
    public string $type = 'mix';  // default: tampilkan semua

    protected $model = Transaksi::class;

    /**
     * Terima parameter `type` dari blade:
     * @livewire('table.transaksi-table', ['type' => 'barang'])
     */
    public function mount($type = 'mix')
    {
        $this->type = in_array($type, ['barang','jasa','mix']) ? $type : 'mix';
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id_transaksi');
    }

    /**
     * Override builder() untuk mem‐filter berdasarkan $this->type
     */
    public function builder()
    {
        $q = Transaksi::with(['konsumen','teknisi','points']);
        return match($this->type) {
            'barang' => $q->whereJsonLength('id_barang','>',0),
            'jasa'   => $q->whereJsonLength('id_jasa','>',0),
            default  => $q,
        };
    }

    public function updateStatus(int $id, string $newStatus): void
    {
        $t = Transaksi::find($id);
        if (!$t) return;

        if (in_array($newStatus, ['proses','selesai','diambil'], true)) {
            $t->status_service = $newStatus;
            $t->save();

            // jika berpindah ke 'diambil', beri +1 poin
            if ($newStatus === 'diambil' && $t->konsumen) {
                $t->konsumen->increment('jumlah_point', 1);
            }
        }

        $this->dispatch('refreshDatatable');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id_transaksi')
                ->sortable()->searchable(),

            Column::make('Konsumen', 'konsumen.nama_konsumen')
                ->sortable(fn($q,$dir) =>
                    $q->join('konsumens','transaksis.id_konsumen','=','konsumens.id_konsumen')
                      ->orderBy('konsumens.nama_konsumen',$dir)
                )
                ->searchable(),

            Column::make('Tanggal', 'tanggal_transaksi')
                ->sortable()->searchable(),

            Column::make('Total', 'total_harga')
                ->sortable()
                ->format(fn($v) => 'Rp '.number_format($v,0,',','.')),

            Column::make('Bayar', 'metode_pembayaran')
                ->sortable()->searchable(),

            // hanya render dropdown jika transaksi punya jasa (atau type=mix & ada jasa)
            Column::make('Status Service')
                ->html()
                ->format(function($_, Transaksi $row) {
                    $hasJasa = count($row->jasaModels()) > 0;

                    if (! $hasJasa) {
                        return '<span class="text-gray-500">–</span>';
                    }

                    $opts = ['proses'=>'Proses','selesai'=>'Selesai','diambil'=>'Diambil'];
                    $sel  = fn($v)=> $row->status_service===$v ? 'selected' : '';

                    $html = '<select wire:change="updateStatus('.$row->id_transaksi.', $event.target.value)"'
                          . ' class="border rounded px-2 py-1 bg-white">';
                    foreach ($opts as $val=>$label) {
                        $html .= "<option value=\"{$val}\" {$sel($val)}>{$label}</option>";
                    }
                    return $html . '</select>';
                }),

            Column::make('Estimasi', 'estimasi_pengerjaan')
                ->sortable()->searchable(),

            Column::make('Teknisi', 'teknisi.nama_teknisi')
                ->sortable(fn($q,$dir)=>
                    $q->leftJoin('teknisis','transaksis.id_teknisi','=','teknisis.id_teknisi')
                      ->orderBy('teknisis.nama_teknisi',$dir)
                )
                ->searchable(),

            LinkColumn::make('Aksi')
                ->title(fn($row)=>'Detail')
                ->location(fn($row)=>route('transaksi.show',$row->id_transaksi)),
        ];
    }
}
