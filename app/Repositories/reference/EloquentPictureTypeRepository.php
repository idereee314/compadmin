<?php 

namespace reference;

use reference;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentPictureTypeRepository implements PictureTypeRepository
{
    public function find($id)
    {
        return PictureType::find($id);
    }

    public function all()
    {
        return PictureType::all();
    }

    public function getByObjectType($object)
    {
        //DB::connection()->enableQueryLog();
        $type = "";
        if(@$object)
        {
            $qry = PictureType::where('object_type', $object);
            $type = $qry->get();
        }
        /*
        $queries = DB::getQueryLog();
        dd($queries);
        */
        
        return $type;
    }

    public function create($input)
    {
        $pictureType = new PictureType;

        $pictureType->code = @$input['code'];
        $pictureType->description = @$input['description'];
        $pictureType->height = @$input['height'];
        $pictureType->width = @$input['width'];
        $pictureType->object_type = @$input['object_type'];
        $pictureType->dir_url = @$input['dir_url'];

        $pictureType->save();
    }

    public function delete($id)
    {
        $pictureType = PictureType::find($id);
        $pictureType->delete();
    }

    public function update($id, $data)
    {
        $pictureType = PictureType::find($id);

        $pictureType->code = @$data['code'];
        $pictureType->description = @$data['description'];
        $pictureType->height = @$data['height'];
        $pictureType->width = @$data['width'];
        $pictureType->object_type = @$data['object_type'];
        $pictureType->dir_url = @$data['dir_url'];

        $pictureType->save();
    }

    public function getDatatableList($searchData)
    {
        $pictureType = PictureType::select('*');
        
        $data = Datatables::of($pictureType)
        ->filter(function ($pictureType) use ($searchData) {
            if($searchData->has('code') && $searchData->get('code') !== null)
            {
                $pictureType->whereRaw('LOWER(code) like ?', array('%'.mb_strtolower($searchData->get('code')).'%'));
            }

            if($searchData->has('description') && $searchData->get('description') !== null)
            {
                $pictureType->whereRaw('LOWER(description) like ?', array('%'.mb_strtolower($searchData->get('description')).'%'));
            }

            if($searchData->has('object_type') && $searchData->get('object_type') !== null)
            {
                $pictureType->where('object_type', $searchData->get('object_type'));
            }
        })
        ->addColumn('action', function ($cType) {
            $actionHtml = "";
            $actionHtml = '<div class="btn-group dropup">';
            $actionHtml .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">';
            $actionHtml .= '<i class="fa fa-server"></i>';
            $actionHtml .= '<span class="sr-only">Toggle Dropdown</span>';
            $actionHtml .= '</button>';
            $actionHtml .= '<ul class="dropdown-menu pull-right">';
            
            $actionHtml .= '<li><a href="#" onclick="pictureTypeEdit('.$cType->id.')">'.trans('display.general_edit').'</a></li>';

            $actionHtml .= '<li class="divider"></li>';
            
            $actionHtml .= '<li><a href="#" name="source-data" onclick="pictureTypeDelete('.$cType->id.')" class="picture-type-delete" data-pictureTypeId="'.$cType->id.'">'.trans('display.general_delete').'</a>';
                           
            $actionHtml .= '</ul>';
            $actionHtml .= '</div>';

            return $actionHtml;
        })
        ->rawColumns(['show_image','action'])
        ->make(true);

        return $data;
    }
}