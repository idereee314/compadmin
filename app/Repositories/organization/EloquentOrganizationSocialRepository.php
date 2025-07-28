<?php 

namespace organization;

use organization\OrganizationSocial;

use Datatables;
use Session;
use Config;
use \DB;
use Auth;

class EloquentOrganizationSocialRepository implements OrganizationSocialRepository
{
    public function find($id)
    {
        return OrganizationSocial::find($id);
    }

    public function all()
    {
        return OrganizationSocial::all();
    }

    public function create($input)
    {
        $organizationSocial = new OrganizationSocial;

        $organizationSocial->name = @$input['name'];
        $organizationSocial->social_type = @$input['social_type'];
        $organizationSocial->url = @$input['url'];
        $organizationSocial->organization_id = @$input['organization_id'];
        $organizationSocial->created_by = Auth::user()->id;

        $organizationSocial->save();
    }

    public function delete($id)
    {
        $organizationSocial = OrganizationSocial::find($id);
        $organizationSocial->delete();
    }

    public function update($id, $data)
    {
        $organizationSocial = OrganizationSocial::find($id);

        $organizationSocial->name = @$data['name'];
        $organizationSocial->social_type = @$data['social_type'];
        $organizationSocial->url = @$data['url'];
        $organizationSocial->updated_by = Auth::user()->id;

        $organizationSocial->save();
    }
}