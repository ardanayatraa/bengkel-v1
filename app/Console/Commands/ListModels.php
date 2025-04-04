<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ListModels extends Command
{
    protected $signature = 'list:models';
    protected $description = 'List all model names in app/Models';

    public function handle()
    {
        $modelPath = app_path('Models');

        if (!File::exists($modelPath)) {
            $this->error("Folder app/Models tidak ditemukan.");
            return;
        }

        $modelFiles = File::files($modelPath);

        $models = collect($modelFiles)
            ->filter(fn ($file) => $file->getExtension() === 'php')
            ->map(fn ($file) => $file->getFilenameWithoutExtension())
            ->sort()
            ->values();

        if ($models->isEmpty()) {
            $this->info("Tidak ada model ditemukan di folder app/Models.");
            return;
        }

        $this->info("Daftar model yang ditemukan:");
        foreach ($models as $model) {
            $this->line("- " . $model);
        }
    }
}
