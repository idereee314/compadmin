<?php 

namespace organization;

use organization\OrganizationBanner;

use Datatables;
use Session;
use Config;
use \DB;
use Auth;

class EloquentOrganizationBannerRepository implements OrganizationBannerRepository
{
    public function find($id)
    {
        return OrganizationBanner::find($id);
    }

    public function all()
    {
        return OrganizationBanner::all();
    }

    public function create($input)
    {
        $organizationBanner = new OrganizationBanner;

        $organizationBanner->organization_id = @$input['organization_id'];
        $organizationBanner->name = @$input['name'];
        $organizationBanner->description = @$input['description'];
        $organizationBanner->location_desc = @$input['location_desc'];
        $organizationBanner->begin_date = @$input['begin_date'];
        $organizationBanner->end_date = @$input['end_date'];

        $organizationBanner->save();
    }

    public function delete($id)
    {
        $organizationBanner = OrganizationBanner::find($id);
        $organizationBanner->delete();
    }

    public function update($id, $data)
    {
        $organizationBanner = OrganizationBanner::find($id);

        $organizationBanner->name = @$data['name'];
        $organizationBanner->description = @$data['description'];
        $organizationBanner->location_desc = @$data['location_desc'];
        $organizationBanner->begin_date = @$data['begin_date'];
        $organizationBanner->end_date = @$data['end_date'];
        $organizationBanner->is_active = @$data['is_active'] ? $data['is_active'] : FALSE;

        $organizationBanner->save();
    }
}