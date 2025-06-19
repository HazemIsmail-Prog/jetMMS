<?php

namespace App\Providers;

use App\Http\Resources\AreaResource;
use App\Http\Resources\StatusResource;
use App\Models\Area;
use App\Models\Setting;
use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        View::share('globalAreasResource', Cache::rememberForever('globalAreasResource', function() {
            return AreaResource::collection(Area::all());
        }));

        View::share('globalStatusesResource', Cache::rememberForever('globalStatusesResource', function() {
            return StatusResource::collection(Status::all());
        }));

        Model::preventLazyLoading(!app()->isProduction());

        $this->cleanupOldUploads();

        if (Schema::hasTable('settings')) {
            View::share('settings', Setting::find(1));
        }
    }

    // This function to delete livewire-tmp files older then 5 minutes
    protected function cleanupOldUploads()
    {
        if (FileUploadConfiguration::isUsingS3()) return;

        $storage = FileUploadConfiguration::storage();

        foreach ($storage->allFiles(FileUploadConfiguration::path()) as $filePathname) {
            // On busy websites, this cleanup code can run in multiple threads causing part of the output
            // of allFiles() to have already been deleted by another thread.
            if (!$storage->exists($filePathname)) continue;

            $yesterdaysStamp = now()->subMinutes(5)->timestamp;
            if ($yesterdaysStamp > $storage->lastModified($filePathname)) {
                $storage->delete($filePathname);
            }
        }
    }
}
