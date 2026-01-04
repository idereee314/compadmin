<?php 

namespace organization;

use organization\OrganizationEvent;

use Datatables;
use Session;
use Config;
use \DB;
use Auth;

class EloquentOrganizationEventRepository implements OrganizationEventRepository
{
    public function find($id)
    {
        return OrganizationEvent::find($id);
    }

    public function all()
    {
        return OrganizationEvent::all();
    }

    public function create($input)
    {
        $orgEvent = new OrganizationEvent;
        $orgEvent->event_id = $input['event_id'];
        $orgEvent->organization_id = @$input['organization_id'];
        $orgEvent->role = @$input['role'];

        $orgEvent->save();
    }

    public function createMany($input)
    {
        $orgEvent = new OrganizationEvent;
        $orgEvent->event_id = $input['event_id'];
        $orgEvent->organization_id = @$input['organization_id'];
        $orgEvent->role = @$input['role'];

        $orgEvent->save();
    }

    public function delete($id)
    {
        $orgEvent = OrganizationEvent::find($id);
        $orgEvent->delete();
    }

    public function update($id, $data)
    {
        $orgEvent = OrganizationEvent::find($id);
        $orgEvent->event_id = @$data['event_id'];
        $orgEvent->organization_id = @$input['organization_id'];
        $orgEvent->role = @$input['role'];

        $orgEvent->save();
    }
}