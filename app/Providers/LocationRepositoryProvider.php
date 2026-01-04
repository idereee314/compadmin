<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LocationRepositoryProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Reference
        $this->app->bind("location\\reference\ObjectTypeRepository", "location\\reference\EloquentObjectTypeRepository");
        $this->app->bind("location\\reference\RoadObjectTypeRepository", "location\\reference\EloquentRoadObjectTypeRepository");
        $this->app->bind("location\\reference\EntryTypeRepository", "location\\reference\EloquentEntryTypeRepository");

        // Object Location
        $this->app->bind("location\object\ObjectLocationRepository", "location\object\EloquentObjectLocationRepository");
        $this->app->bind("location\object\EntranceRepository", "location\object\EloquentEntranceRepository");

        // Unit
        $this->app->bind("location\unit\AimagCityRepository", "location\unit\EloquentAimagCityRepository");
        $this->app->bind("location\unit\SoumDistrictRepository", "location\unit\EloquentSoumDistrictRepository");
        $this->app->bind("location\unit\BagKhorooRepository", "location\unit\EloquentBagKhorooRepository");

        $this->app->bind("location\configuration\\ConfigurationRepository", "location\configuration\EloquentConfigurationRepository");
    }
}
