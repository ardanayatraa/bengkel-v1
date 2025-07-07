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
                ->sortable()
                ->format(function($value, $row) {
                    $badgeClass = strtolower($value) === 'member'
                        ? 'bg-blue-100 text-blue-800'
                        : 'bg-gray-100 text-gray-800';

                    return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $badgeClass . '">'
                           . ucfirst($value ?: 'Reguler') . '</span>';
                })
                ->html(),

            Column::make("Kode Referral", "kode_referral")
                ->sortable()
                ->format(function($value, $row) {
                    if ($value && strtolower($row->keterangan) === 'member') {
                        return '
                        <div class="flex items-center space-x-1">
                            <code class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-mono rounded">'
                            . $value . '</code>
                            <button onclick="copyReferralCode(\'' . $value . '\')"
                                    class="text-gray-400 hover:text-gray-600 text-xs"
                                    title="Copy kode">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button onclick="regenerateReferral(' . $row->id_konsumen . ')"
                                    class="text-blue-400 hover:text-blue-600 text-xs"
                                    title="Generate ulang">
                                <i class="fas fa-sync"></i>
                            </button>
                        </div>';
                    }
                    return '<span class="text-gray-400 text-xs">-</span>';
                })
                ->html(),

            Column::make("Created at", "created_at")
                ->sortable()
                ->format(fn($value) => $value ? $value->format('d/m/Y H:i') : '-'),

            Column::make("Updated at", "updated_at")
                ->sortable()
                ->format(fn($value) => $value ? $value->format('d/m/Y H:i') : '-'),

            Column::make('Member')->label(
                function ($row) {
                    if (strtolower($row->keterangan) === 'member') {
                        return '<a href="' . route('konsumen.cetak-kartu', ['konsumen' => $row->id_konsumen]) . '"
                                    class="px-2 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700"
                                    target="_blank">
                                    Cetak Kartu
                                </a>';
                    } else {
                        return '<button disabled
                                    class="px-2 py-1 bg-gray-300 text-gray-500 rounded text-xs cursor-not-allowed"
                                    title="Hanya untuk member">
                                    Cetak Kartu
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

    /**
     * Method untuk handle regenerate referral via Livewire
     */
    public function regenerateReferral($konsumenId)
    {
        try {
            $konsumen = Konsumen::findOrFail($konsumenId);

            if (strtolower($konsumen->keterangan) !== 'member') {
                $this->dispatch('show-notification', [
                    'message' => 'Hanya member yang bisa memiliki kode referral',
                    'type' => 'error'
                ]);
                return;
            }

            $newCode = $this->generateKodeReferral($konsumen->nama_konsumen);
            $konsumen->update(['kode_referral' => $newCode]);

            $this->dispatch('show-notification', [
                'message' => 'Kode referral berhasil diperbarui: ' . $newCode,
                'type' => 'success'
            ]);

            // Refresh data table
            $this->resetPage();

        } catch (\Exception $e) {
            $this->dispatch('show-notification', [
                'message' => 'Terjadi kesalahan saat memperbarui kode referral',
                'type' => 'error'
            ]);
        }
    }

    /**
     * Generate unique kode referral
     */
    private function generateKodeReferral($namaKonsumen)
    {
        do {
            // Format: 3 huruf dari nama + 4 digit random
            $namaPrefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $namaKonsumen), 0, 3));
            if (strlen($namaPrefix) < 3) {
                $namaPrefix = str_pad($namaPrefix, 3, 'X');
            }

            $kode = $namaPrefix . sprintf('%04d', rand(1000, 9999));
        } while (Konsumen::where('kode_referral', $kode)->exists());

        return $kode;
    }
}
