<?php namespace reference;

use reference\EntryConfigBelt;
use core\sessions\Sessions;

use Hash;
use Log;
use ConfigHelper;
use DateHelper;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;

use SecurityHelper;
use Carbon;
use Session;
use Config;

class EloquentEntryConfigBeltRepository implements EntryConfigBeltRepository {

	public function all()
	{
		return EntryConfigBelt::all();
	}

	public function allPaginate()
	{
		return EntryConfigBelt::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EntryConfigBelt::find($id);
	}

	public function create($input)
	{
		$entryConfigBelt = new EntryConfigBelt;

		$entryConfigBelt->entry_id = $input['entry_id'];
		$entryConfigBelt->name = $input['name'];
		$entryConfigBelt->name_en = $input['name_en'];

		$entryConfigBelt->save();

		return $entryConfigBelt;
	}

 	public function update($id, $input)
	{
		$entryConfigBelt = $this->find($id);
		
		$entryConfigBelt->entry_id = $input['entry_id'];
		$entryConfigBelt->name = $input['name'];
		$entryConfigBelt->name_en = $input['name_en'];

		$entryConfigBelt->save();

		return $entryConfigBelt;
	}

	public function delete($id)
	{
		$entryConfigBelt = $this->find($id);

		$entryConfigBelt->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = EntryConfigBelt::select('*');

        $data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                if($searchData->has('username') && $searchData->get('username') !== null)
                {
                    $qry->whereRaw('LOWER(sd_user.username) like ?', array('%'.mb_strtolower($searchData->get('username')).'%'));
                }

                if($searchData->has('firstname') && $searchData->get('firstname') !== null)
                {
                    $qry->whereRaw('LOWER(firstname) like ?', array('%'.mb_strtolower($searchData->get('firstname')).'%'));
				}

                if($searchData->has('user_mail') && $searchData->get('user_mail') !== null)
                {
                    $qry->whereRaw('LOWER(sd_user.email) like ?', array('%'.mb_strtolower($searchData->get('user_mail')).'%'));
                }
            })
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
            ->addColumn('action', function ($entryConfigBelt) {

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="EntryConfigBeltEdit('.$entryConfigBelt->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="EntryConfigBeltDelete('.$entryConfigBelt->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['action'])
            ->make(true);

        return $data;
	}
}
