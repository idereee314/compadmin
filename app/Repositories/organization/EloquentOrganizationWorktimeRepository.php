<?php 

namespace organization;

use organization\OrganizationWorktime;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentOrganizationWorktimeRepository implements OrganizationWorktimeRepository
{
    public function find($id)
    {
        return OrganizationWorktime::find($id);
    }

    public function all()
    {
        return OrganizationWorktime::all();
    }

    public function create($input)
    {
        if ($input['work_day'][0] == "0") {
            for ($i=1; $i < 6; $i++) { 
                $organizationWorktime = new OrganizationWorktime;

                $organizationWorktime->work_day = $i;
                $organizationWorktime->start_time = @$input['start_time'];
                $organizationWorktime->end_time = @$input['end_time'];
                $organizationWorktime->organization_id = @$input['organization_id'];
    
                $organizationWorktime->save();
            }
        }
        else
        {
            foreach ($input['work_day'] as $key => $value) {
                $organizationWorktime = new OrganizationWorktime;

                $organizationWorktime->work_day = $value;
                $organizationWorktime->start_time = @$input['start_time'];
                $organizationWorktime->end_time = @$input['end_time'];
                $organizationWorktime->organization_id = @$input['organization_id'];

                $organizationWorktime->save();
            }
        }
    }

    public function delete($id)
    {
        $organizationWorktime = OrganizationWorktime::find($id);
        $organizationWorktime->delete();
    }

    public function update($id, $data)
    {
        $organizationWorktime = OrganizationWorktime::find($id);

        $organizationWorktime->start_time = @$data['start_time'];
        $organizationWorktime->end_time = @$data['end_time'];

        $organizationWorktime->save();
    }
}