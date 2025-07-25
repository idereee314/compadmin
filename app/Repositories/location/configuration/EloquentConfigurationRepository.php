<?php 

namespace location\configuration;

use location\configuration\Configuration;

use Datatables;
use Session;
use Config;
use \DB;
use Auth;

class EloquentConfigurationRepository implements ConfigurationRepository
{
    public function find($id)
    {
        return Configuration::find($id);
    }

    public function all()
    {
        return Configuration::all();
    }

    public function create($input)
    {
        $config = new Configuration;

        $config->tag_id = @$input['tag_id'];
        $config->mn_description = @$input['mn_description'];
        $config->tag_key = @$input['tag_key'];
        $config->tag_value = @$input['tag_value'];
        $config->priority = @$input['priority'];
        $config->maxspeed = @$input['maxspeed'];
        $config->maxspeed_forward = @$input['maxspeed_forward'];
        $config->maxspeed_backward = @$input['maxspeed_backward'];
        $config->force = @$input['force'];

        $config->save();
        $config->roadObjectType()->attach(@$input['road_object_type']);
    }

    public function delete($id)
    {
        $config = Configuration::find($id);
        $config->delete();
    }

    public function update($id, $data)
    {
        $config = Configuration::find($id);

        $config->tag_id = @$data['tag_id'];
        $config->mn_description = @$data['mn_description'];
        $config->tag_key = @$data['tag_key'];
        $config->tag_value = @$data['tag_value'];
        $config->priority = @$data['priority'];
        $config->maxspeed = @$data['maxspeed'];
        $config->maxspeed_forward = @$data['maxspeed_forward'];
        $config->maxspeed_backward = @$data['maxspeed_backward'];
        $config->force = @$data['force'];

        $config->save();
        $config->roadObjectType()->sync(@$data['road_object_type']);
    }

    public function getDatatableList($searchData)
    {
        $config = Configuration::select('*');
        
        $data = Datatables::of($config)
        ->filter(function ($config) use ($searchData) {
            if($searchData->has('tag_id') && $searchData->get('tag_id') !== null)
            {
                $config->where('tag_id', $searchData->get('tag_id'));
            }

            if($searchData->has('tag_key') && $searchData->get('tag_key') !== null)
            {
                $config->whereRaw('LOWER(tag_key) like ?', array('%'.mb_strtolower($searchData->get('tag_key')).'%'));
            }

            if($searchData->has('tag_value') && $searchData->get('tag_value') !== null)
            {
                $config->whereRaw('LOWER(tag_value) like ?', array('%'.mb_strtolower($searchData->get('tag_value')).'%'));
            }
        })
        ->editColumn('road_object_type', function ($config) {
            $roadObjectType = @$config->roadObjectType->pluck('description')->toArray();

            $roadObjectTypeDescription = "";

            foreach ($roadObjectType as $key => $value) {
                $roadObjectTypeDescription .= ", ".$value;
            }

            return ltrim($roadObjectTypeDescription, ",");
        })
        ->addColumn('action', function ($config) {
            $actionHtml = "";
            $actionHtml = '<div class="btn-group dropup">';
            $actionHtml .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">';
            $actionHtml .= '<i class="fa fa-server"></i>';
            $actionHtml .= '<span class="sr-only">Toggle Dropdown</span>';
            $actionHtml .= '</button>';
            $actionHtml .= '<ul class="dropdown-menu pull-right">';
            
            $actionHtml .= '<li><a href="#" onclick="configEdit('.$config->id.')">'.trans('display.general_edit').'</a></li>';

            $actionHtml .= '<li class="divider"></li>';
            
            $actionHtml .= '<li><a href="#" name="source-data" onclick="configDelete('.$config->id.')" class="organization-delete" data-organizationid="'.$config->id.'">'.trans('display.general_delete').'</a>';
                           
            $actionHtml .= '</ul>';
            $actionHtml .= '</div>';

            return $actionHtml;
        })
        ->rawColumns(['road_object_type','action'])
        ->make(true);

        return $data;
    }
}