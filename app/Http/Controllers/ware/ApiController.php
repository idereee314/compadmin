<?php

namespace ware;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Input;
use Illuminate\Support\Str;
use Validator;
use \Session as Session;
use Config;
use HTML;
use Log;
use Cache;

use academy\AcademyRepository as Academy;
use organization\OrganizationRepository as Organization;
use member\MemberRepository as Member;
use sport\SportRepository as Sport;
use event\EventRepository as Event;
use event\EventRegistrationRepository as EventRegistration;

class ApiController extends Controller 
{
    public function __construct(Academy $academy, Organization $organization, Member $member, Sport $sport, Event $event, EventRegistration $eventRegistration)
    {
        $this->academy = $academy;
        $this->organization = $organization;
        $this->member = $member;
        $this->sport = $sport;
        $this->event = $event;
        $this->eventRegistration = $eventRegistration;
    }

    public function membershipList()
    {
        $input = Input::all();
        
        $academy = $this->academy->getAcademyListBySport($input['sport_id']);
        
        return response()->json($academy);
    }   

}