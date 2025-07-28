<?php 

namespace organization;

use organization\OrganizationContact;

use Datatables;
use Session;
use Config;
use \DB;
use Auth;

class EloquentOrganizationContactRepository implements OrganizationContactRepository
{
    public function find($id)
    {
        return OrganizationContact::find($id);
    }

    public function all()
    {
        return OrganizationContact::all();
    }

    public function create($input)
    {
        $organizationContact = new OrganizationContact;

        $organizationContact->organization_id = @$input['organization_id'];
        $organizationContact->contact_type_id = @$input['contact_type'];
        $organizationContact->contact_value = @$input['contact_value'];
        $organizationContact->description = @$input['description'];

        $organizationContact->save();
    }

    public function delete($id)
    {
        $organizationContact = OrganizationContact::find($id);
        $organizationContact->delete();
    }

    public function update($id, $data)
    {
        $organizationContact = OrganizationContact::find($id);

        $organizationContact->contact_value = @$data['contact_value'];
        $organizationContact->description = @$data['description'];
        $organizationContact->is_active = @$data['is_active_edit'] ? $data['is_active_edit'] : false;

        $organizationContact->save();
    }
}