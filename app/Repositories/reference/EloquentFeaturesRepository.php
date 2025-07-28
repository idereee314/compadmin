<?php 

namespace reference;

use reference\Features;

use Datatables;
use Session;
use Config;
use \DB;
use Auth;

class EloquentFeaturesRepository implements FeaturesRepository
{
    public function find($id)
    {
        // dd($id." baina aa");

        return Features::find($id);
    }

    public function all()
    {
        return Features::all();
    }

    public function create($input)
    {
        $features = new Features;
        $features->name = @$input['name'];
        $features->description = @$input['description'];

        $features->save();

        return $features;
    }

    public function delete($id)
    {
        $features = Features::find($id);
        $features->delete();
    }

    public function update($id, $data)
    {
        $features = Features::find($id);

        $features->name = @$data['name'];
        $features->description = @$data['description'];
        $features->is_active = @$data['is_active'] ? true : false;

        $features->save();

        return $features;
    }

    public function getDatatableList($searchData)
    {
        $features = Features::select('*');
        
        $data = Datatables::of($features)
        ->filter(function ($features) use ($searchData) {
            if($searchData->has('name') && $searchData->get('name') !== null)
            {
                $features->whereRaw('LOWER(name) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'));
            }

            if($searchData->has('is_active') && $searchData->get('is_active') !== null)
            {
                $features->where('is_active', $searchData->get('is_active'));
            }
        })
        ->editColumn('is_active', function ($features) {
            return Config::get('smart.is_active')[$features->is_active];
        })
        ->editColumn('images', function ($feature) {
            if ($feature->image64) {
                return '<img src="'.$feature->image64.'" alt="">';
            }

            return " ";
        })
        ->addColumn('action', function ($feature) {
            $actionHtml = "";
            $actionHtml = '<div class="btn-group dropup">';
            $actionHtml .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">';
            $actionHtml .= '<i class="fa fa-server"></i>';
            $actionHtml .= '<span class="sr-only">Toggle Dropdown</span>';
            $actionHtml .= '</button>';
            $actionHtml .= '<ul class="dropdown-menu pull-right">';
            
            $actionHtml .= '<li><a href="#" onclick="featuresEdit('.$feature->id.')">'.trans('display.general_edit').'</a></li>';

            $actionHtml .= '<li class="divider"></li>';
            
            $actionHtml .= '<li><a href="#" name="source-data" onclick="featuresDelete('.$feature->id.')" class="features-delete" data-featuresid="'.$feature->id.'">'.trans('display.general_delete').'</a>';
                           
            $actionHtml .= '</ul>';
            $actionHtml .= '</div>';

            return $actionHtml;
        })
        ->rawColumns(['images','action'])
        ->make(true);

        return $data;
    }
}