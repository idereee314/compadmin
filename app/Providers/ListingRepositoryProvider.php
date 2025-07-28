<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ListingRepositoryProvider extends ServiceProvider {

    public function boot() {
    }

    public function register() {
        // Reference
        $this->app->bind("listing\\reference\category\CategoryRepository", "listing\\reference\category\EloquentCategoryRepository");
        $this->app->bind("listing\\reference\\features\FeaturesRepository", "listing\\reference\\features\EloquentFeaturesRepository");
        $this->app->bind("listing\\reference\contactType\ContactTypeRepository", "listing\\reference\contactType\EloquentContactTypeRepository");
        $this->app->bind("listing\\reference\pictureType\PictureTypeRepository", "listing\\reference\pictureType\EloquentPictureTypeRepository");
        $this->app->bind("listing\\reference\organization\OrganizationStatusRepository", "listing\\reference\organization\EloquentOrganizationStatusRepository");
        $this->app->bind("listing\\reference\organization\OrganizationTypeRepository", "listing\\reference\organization\EloquentOrganizationTypeRepository");
        $this->app->bind("listing\\reference\service\ServiceRepository", "listing\\reference\service\EloquentServiceRepository");

        // Organization
        $this->app->bind("listing\organization\OrganizationRepository", "listing\organization\EloquentOrganizationRepository");
        $this->app->bind("listing\organization\OrganizationWorktimeRepository", "listing\organization\EloquentOrganizationWorktimeRepository");
        $this->app->bind("listing\organization\OrganizationAddressRepository", "listing\organization\EloquentOrganizationAddressRepository");
        $this->app->bind("listing\organization\OrganizationPictureRepository", "listing\organization\EloquentOrganizationPictureRepository");
        $this->app->bind("listing\organization\OrganizationContactRepository", "listing\organization\EloquentOrganizationContactRepository");
        $this->app->bind("listing\organization\OrganizationBannerRepository", "listing\organization\EloquentOrganizationBannerRepository");
        $this->app->bind("listing\organization\OrganizationSocialRepository", "listing\organization\EloquentOrganizationSocialRepository");
        $this->app->bind("listing\organization\OrganizationEventRepository", "listing\organization\EloquentOrganizationEventRepository");

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
