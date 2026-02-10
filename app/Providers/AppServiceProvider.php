<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use event\EventConfigDaysRepository;
use event\EventMatchesRespository;
use event\EloquentEventConfigDaysRepository;
use event\EloquentEventMatchesRespository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('user\CompadUserRepository', 'user\EloquentCompadUserRepository'); 
        $this->app->bind('user\UserRepository', 'user\EloquentUserRepository'); 
        $this->app->bind('member\MemberRepository', 'member\EloquentMemberRepository');
        $this->app->bind('academy\AcademyRepository', 'academy\EloquentAcademyRepository');  
        $this->app->bind('event\EventRegistrationRepository', 'event\EloquentEventRegistrationRepository'); 
        $this->app->bind('event\EventRegistrationStatusRepository', 'event\EloquentEventRegistrationStatusRepository'); 
        $this->app->bind('event\EventConfigRepository', 'event\EloquentEventConfigRepository'); 
        $this->app->bind('event\EventAwardRepository', 'event\EloquentEventAwardRepository');
        $this->app->bind('event\EventRepository', 'event\EloquentEventRepository');
        $this->app->bind('event\EventCategoryRepository', 'event\EloquentEventCategoryRepository');
        $this->app->bind('event\EventConfigDaysRepository', 'event\EloquentEventConfigDaysRepository');
        $this->app->bind('event\EventMatchesRespository', 'event\EloquentEventMatchesRespository');
        $this->app->bind('event\EventSportRepository', 'event\EloquentEventSportRepository');
        $this->app->bind('event\EventRankSeasonRepository', 'event\EloquentEventRankSeasonRepository');
        $this->app->bind('event\EventUserRepository', 'event\EloquentEventUserRepository'); 
        $this->app->bind('user\CompadRoleRepository', 'user\EloquentCompadRoleRepository'); 
        $this->app->bind('sport\SportRepository', 'sport\EloquentSportRepository');  
        $this->app->bind('event\EventTeamRegistrationRepository', 'event\EloquentEventTeamRegistrationRepository');
        $this->app->bind('event\EventTeamRegistrationStatusRepository', 'event\EloquentEventTeamRegistrationStatusRepository');  
        $this->app->bind('team\TeamRepository', 'team\EloquentTeamRepository');  
        $this->app->bind('member\TeamMemberRepository', 'member\EloquentTeamMemberRepository');  
        $this->app->bind('member\TeamMemberAttributeRepository', 'member\EloquentTeamMemberAttributeRepository');  
        $this->app->bind('event\EventTypeRepository', 'event\EloquentEventTypeRepository');
        $this->app->bind('event\EventPictureRepository', 'event\EloquentEventPictureRepository');
        $this->app->bind('event\EventLocationRepository', 'event\EloquentEventLocationRepository');
        $this->app->bind('event\EventDivisionRepository', 'event\EloquentEventDivisionRepository');
        $this->app->bind('event\EventBracketTypeRepository', 'event\EloquentEventBracketTypeRepository');

        //Organization
        $this->app->bind('organization\OrganizationRepository', 'organization\EloquentOrganizationRepository'); 
        $this->app->bind('organization\OrganizationEventRepository', 'organization\EloquentOrganizationEventRepository'); 
        $this->app->bind('organization\OrganizationAddressRepository', 'organization\EloquentOrganizationAddressRepository');
        $this->app->bind('organization\OrganizationBannerRepository', 'organization\EloquentOrganizationBannerRepository');
        $this->app->bind('organization\OrganizationContactRepository', 'organization\EloquentOrganizationContactRepository');
        $this->app->bind('organization\OrganizationSocialRepository', 'organization\EloquentOrganizationSocialRepository');
        $this->app->bind('organization\OrganizationWorktimeRepository', 'organization\EloquentOrganizationWorktimeRepository');

        $this->app->bind('country\CountryRepository', 'country\EloquentCountryRepository');
        $this->app->bind('event\EventRefundRequestRepository', 'event\EloquentEventRefundRequestRepository'); 

        // Membership
        $this->app->bind('membership\MembershipTypeRepository', 'membership\EloquentMembershipTypeRepository'); 
        $this->app->bind('membership\MembershipAcademyRepository', 'membership\EloquentMembershipAcademyRepository'); 

        //reference
        $this->app->bind('reference\EventEntriesRepository', 'reference\EloquentEventEntriesRepository'); 
        $this->app->bind('reference\EntryConfigAgeRepository', 'reference\EloquentEntryConfigAgeRepository'); 
        $this->app->bind('reference\EntryConfigBeltRepository', 'reference\EloquentEntryConfigBeltRepository'); 
        $this->app->bind('reference\EntryConfigRepository', 'reference\EloquentEntryConfigRepository'); 
        $this->app->bind('reference\EntryConfigWeightRepository', 'reference\EloquentEntryConfigWeightRepository'); 
        $this->app->bind('reference\EventEntriesFeeRepository', 'reference\EloquentEventEntriesFeeRepository'); 
        $this->app->bind('reference\BeltGroupRepository', 'reference\EloquentBeltGroupRepository'); 
        $this->app->bind('reference\EntryResultTypeRepository', 'reference\EloquentEntryResultTypeRepository');
        $this->app->bind('reference\EventToplistPointRepository', 'reference\EloquentEventToplistPointRepository');
        $this->app->bind('reference\ConfigMatRepository', 'reference\EloquentConfigMatRepository');
        $this->app->bind("reference\PictureTypeRepository", "reference\EloquentPictureTypeRepository");
        $this->app->bind('reference\CategoryRepository', 'reference\EloquentCategoryRepository');
        $this->app->bind('reference\ServiceRepository', 'reference\EloquentServiceRepository');
        $this->app->bind("reference\OrganizationStatusRepository", "reference\EloquentOrganizationStatusRepository");
        $this->app->bind("reference\OrganizationTypeRepository", "reference\EloquentOrganizationTypeRepository");
        $this->app->bind("reference\FeaturesRepository", "reference\EloquentFeaturesRepository");
        $this->app->bind("reference\ContactTypeRepository", "reference\EloquentContactTypeRepository");
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
