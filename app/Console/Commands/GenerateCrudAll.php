<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

class GenerateCrudAll extends Command
{
    protected $signature = 'crud:all';
    protected $description = 'Generate CRUD (Model, Migration, Controller, Views, Routes) untuk semua model yang memiliki fillable';

    public function handle()
    {
        $modelPath = app_path('Models');

        if (!File::exists($modelPath)) {
            $this->error("Folder app/Models tidak ditemukan!");
            return;
        }

        $models = collect(File::files($modelPath))
            ->map(fn ($file) => pathinfo($file->getFilename(), PATHINFO_FILENAME))
            ->filter(fn ($model) => $this->hasFillable($model))
            ->values();

        if ($models->isEmpty()) {
            $this->error("Tidak ada model dengan fillable yang ditemukan di folder app/Models!");
            return;
        }

        foreach ($models as $model) {
            $this->info("📌 Membuat CRUD untuk model: $model");

            // 1. Generate Model & Migration jika belum ada
            if (!class_exists("App\\Models\\$model")) {
                $this->callSilent('make:model', ['name' => $model, '-m' => true]);
            }

            // 2. Generate Controller
            $this->callSilent('make:controller', [
                'name' => "{$model}Controller",
                '--resource' => true,
            ]);

            // 3. Generate Views
            $this->generateViews($model);

            // 4. Tambahkan Route
            $this->appendRoute($model);

            $this->info("✅ CRUD untuk $model selesai dibuat!");
        }

        $this->info("🎉 Semua CRUD telah dibuat untuk model yang memiliki fillable!");
    }

    private function hasFillable($model)
    {
        $class = "App\\Models\\$model";

        if (!class_exists($class)) {
            return false;
        }

        $reflector = new ReflectionClass($class);

        if (!$reflector->hasProperty('fillable')) {
            return false;
        }

        $property = $reflector->getProperty('fillable');
        $property->setAccessible(true);
        $fillable = $property->getValue(new $class);

        return !empty($fillable);
    }

    private function generateViews($model)
    {
        $viewPath = resource_path("views/$model");

        if (!File::exists($viewPath)) {
            File::makeDirectory($viewPath, 0755, true);
        }

        $views = [
            'index' => "<h1>Daftar " . Str::plural($model) . "</h1>",
            'create' => "<h1>Tambah $model</h1>",
            'edit' => "<h1>Edit $model</h1>",
            'show' => "<h1>Detail $model</h1>",
        ];

        foreach ($views as $view => $content) {
            $filePath = "$viewPath/$view.blade.php";
            if (!File::exists($filePath)) {
                File::put($filePath, $content);
            }
        }
    }

    private function appendRoute($model)
    {
        $routeFile = base_path('routes/web.php');
        $routeEntry = "Route::resource('" . Str::kebab(Str::plural($model)) . "', " . $model . "Controller::class);";

        if (File::exists($routeFile) && !str_contains(File::get($routeFile), $routeEntry)) {
            File::append($routeFile, "\n" . $routeEntry);
        }
    }
}
