<?php 

namespace reference;

use reference\OrganizationType;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentOrganizationTypeRepository implements OrganizationTypeRepository
{
    public function find($id)
    {
        return OrganizationType::find($id);
    }

    public function all()
    {
        return OrganizationType::all();
    }

    public function create($input)
    {
        $organizationStatus = new OrganizationType;

        $organizationStatus->code = @$input['code'];
        $organizationStatus->name = @$input['name'];

        $organizationStatus->save();
    }

    public function delete($id)
    {
        $organizationStatus = OrganizationType::find($id);
        $organizationStatus->delete();
    }

    public function update($id, $data)
    {
        $organizationStatus = OrganizationType::find($id);

        $organizationStatus->code = @$data['code'];
        $organizationStatus->name = @$data['name'];

        $organizationStatus->save();
    }

    public function getDatatableList($searchData)
    {
        $organizationStatus = OrganizationType::select('*');
        
        $data = Datatables::of($organizationStatus)
        ->filter(function ($organizationStatus) use ($searchData) {
            if($searchData->has('code') && $searchData->get('code') !== null)
            {
                $organizationStatus->whereRaw('LOWER(code) like ?', array('%'.mb_strtolower($searchData->get('code')).'%'));
            }

            if($searchData->has('name') && $searchData->get('name') !== null)
            {
                $organizationStatus->whereRaw('LOWER(name) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'));
            }
        })
        ->addColumn('action', function ($orgType) {
            $actionHtml = "";
            $actionHtml = '<div class="btn-group dropup">';
            $actionHtml .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">';
            $actionHtml .= '<i class="fa fa-server"></i>';
            $actionHtml .= '<span class="sr-only">Toggle Dropdown</span>';
            $actionHtml .= '</button>';
            $actionHtml .= '<ul class="dropdown-menu pull-right">';
            
            $actionHtml .= '<li><a href="#" onclick="organizationStatusEdit('.$orgType->id.')">'.trans('display.general_edit').'</a></li>';

            $actionHtml .= '<li class="divider"></li>';
            
            $actionHtml .= '<li><a href="#" name="source-data" onclick="organizationStatusDelete('.$orgType->id.')" class="picture-type-delete" data-organizationStatusId="'.$orgType->id.'">'.trans('display.general_delete').'</a>';
                           
            $actionHtml .= '</ul>';
            $actionHtml .= '</div>';

            return $actionHtml;
        })
        ->rawColumns(['show_image','action'])
        ->make(true);

        return $data;
    }
}