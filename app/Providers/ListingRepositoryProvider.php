<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ListingRepositoryProvider extends ServiceProvider {

    public function boot() {
    }

    public function register() {
        // Organization
        $this->app->bind("organization\OrganizationRepository", "organization\EloquentOrganizationRepository");
        $this->app->bind("organization\OrganizationWorktimeRepository", "organization\EloquentOrganizationWorktimeRepository");
        $this->app->bind("organization\OrganizationAddressRepository", "organization\EloquentOrganizationAddressRepository");
        $this->app->bind("organization\OrganizationPictureRepository", "organization\EloquentOrganizationPictureRepository");
        $this->app->bind("organization\OrganizationContactRepository", "organization\EloquentOrganizationContactRepository");
        $this->app->bind("organization\OrganizationBannerRepository", "organization\EloquentOrganizationBannerRepository");
        $this->app->bind("organization\OrganizationSocialRepository", "organization\EloquentOrganizationSocialRepository");
        $this->app->bind("organization\OrganizationEventRepository", "organization\EloquentOrganizationEventRepository");

        // // Event
        // $this->app->bind("listing\\event\EventRepository", "listing\\event\EloquentEventRepository");
        // $this->app->bind("listing\\event\EventPictureRepository", "listing\\event\EloquentEventPictureRepository");
        // $this->app->bind("listing\\event\EventLocationRepository", "listing\\event\EloquentEventLocationRepository");

        // Proservice
        $this->app->bind("listing\proservice\ProserviceRepository", "listing\proservice\EloquentProserviceRepository");
        $this->app->bind("listing\proservice\ProserviceAddressRepository", "listing\proservice\EloquentProserviceAddressRepository");

        // Banner
        $this->app->bind("listing\banner\BannerRepository", "listing\banner\EloquentBannerRepository");
    }
}
