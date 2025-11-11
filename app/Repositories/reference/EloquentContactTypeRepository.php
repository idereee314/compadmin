<?php 

namespace reference;

use reference\ContactType;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentContactTypeRepository implements ContactTypeRepository
{
    public function find($id)
    {
        // dd($id." baina aa");

        return ContactType::find($id);
    }

    public function all()
    {
        return ContactType::all();
    }

    public function create($input)
    {
        $contactType = new ContactType;
        $contactType->code = @$input['code'];
        $contactType->description = @$input['description'];

        $contactType->save();

        return $contactType;
    }

    public function delete($id)
    {
        $contactType = ContactType::find($id);
        $contactType->delete();
    }

    public function update($id, $data)
    {
        $contactType = ContactType::find($id);

        $contactType->code = @$data['code'];
        $contactType->description = @$data['description'];
        
        $contactType->save();

        return $contactType;
    }

    public function getDatatableList($searchData)
    {
        $contactType = ContactType::select('*');
        
        $data = Datatables::of($contactType)
        ->filter(function ($contactType) use ($searchData) {
            if($searchData->has('description') && $searchData->get('description') !== null)
            {
                $contactType->whereRaw('LOWER(description) like ?', array('%'.mb_strtolower($searchData->get('description')).'%'));
            }
            if($searchData->has('code') && $searchData->get('code') !== null)
            {
                $contactType->whereRaw('LOWER(code) like ?', array('%'.mb_strtolower($searchData->get('code')).'%'));
            }
        })
        ->editColumn('show_image', function ($contactType) {
            if ($contactType->image64) {
                return '<img style="width:50px; height:50px;" src="'.$contactType->image64.'" alt="">';
            }

            return " ";
        })
        ->addColumn('action', function ($contactType) {
            $actionHtml = "";
            $actionHtml = '<div class="btn-group dropup">';
            $actionHtml .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">';
            $actionHtml .= '<i class="fa fa-server"></i>';
            $actionHtml .= '<span class="sr-only">Toggle Dropdown</span>';
            $actionHtml .= '</button>';
            $actionHtml .= '<ul class="dropdown-menu pull-right">';
            
            $actionHtml .= '<li><a href="#" onclick="contactTypeEdit('.$contactType->id.')">'.trans('display.general_edit').'</a></li>';

            $actionHtml .= '<li class="divider"></li>';
            
            $actionHtml .= '<li><a href="#" name="source-data" onclick="contactTypeDelete('.$contactType->id.')" class="contact-type-delete" data-contactTypeId="'.$contactType->id.'">'.trans('display.general_delete').'</a>';
                           
            $actionHtml .= '</ul>';
            $actionHtml .= '</div>';

            return $actionHtml;
        })
        ->rawColumns(['show_image','action'])
        ->make(true);

        return $data;
    }
}