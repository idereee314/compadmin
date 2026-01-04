<?php 

namespace location\reference;

use location\reference\RoadObjectType;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentRoadObjectTypeRepository implements RoadObjectTypeRepository
{
    public function find($id)
    {
        return RoadObjectType::find($id);
    }

    public function all()
    {
        return RoadObjectType::all();
    }

    public function create($input)
    {
        $roadObjType = new RoadObjectType;

        $roadObjType->code = @$input['code'];
        $roadObjType->description = @$input['description'];
        $roadObjType->description_en = @$input['description_en'];

        $roadObjType->save();
    }

    public function delete($id)
    {
        $roadObjType = RoadObjectType::find($id);
        $roadObjType->delete();
    }

    public function update($id, $data)
    {
        $roadObjType = RoadObjectType::find($id);

        $roadObjType->code = @$data['code'];
        $roadObjType->description = @$data['description'];
        $roadObjType->description_en = @$data['description_en'];

        $roadObjType->save();
    }

    public function getDatatableList($searchData)
    {
        $roadObjType = RoadObjectType::select('*');
        
        $data = Datatables::of($roadObjType)
        ->filter(function ($roadObjType) use ($searchData) {
            if($searchData->has('code') && $searchData->get('code') !== null)
            {
                $roadObjType->where('code', $searchData->get('code'));
            }

            if($searchData->has('description') && $searchData->get('description') !== null)
            {
                $roadObjType->whereRaw('LOWER(description) like ?', array('%'.mb_strtolower($searchData->get('description')).'%'));
            }
        })
        ->addColumn('action', function ($roadObjType) {
            $actionHtml = "";
            $actionHtml = '<div class="btn-group dropup">';
            $actionHtml .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">';
            $actionHtml .= '<i class="fa fa-server"></i>';
            $actionHtml .= '<span class="sr-only">Toggle Dropdown</span>';
            $actionHtml .= '</button>';
            $actionHtml .= '<ul class="dropdown-menu pull-right">';
            
            $actionHtml .= '<li><a href="#" onclick="roadObjectTypeEdit('.$roadObjType->id.')">'.trans('display.general_edit').'</a></li>';

            $actionHtml .= '<li class="divider"></li>';
            
            $actionHtml .= '<li><a href="#" name="source-data" onclick="roadObjectTypeDelete('.$roadObjType->id.')" class="organization-delete" data-organizationid="'.$roadObjType->id.'">'.trans('display.general_delete').'</a>';
                           
            $actionHtml .= '</ul>';
            $actionHtml .= '</div>';

            return $actionHtml;
        })->make(true);

        return $data;
    }
}