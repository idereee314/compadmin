<?php namespace reference;

use reference\EntryConfigWeight;
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

class EloquentEntryConfigWeightRepository implements EntryConfigWeightRepository {

	public function all()
	{
		return EntryConfigWeight::all();
	}

	public function allPaginate()
	{
		return EntryConfigWeight::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EntryConfigWeight::find($id);
	}

	public function create($input)
	{
		$entryConfigWeight = new EntryConfigWeight;

		$entryConfigWeight->entry_id = $input['entry_id'];
		$entryConfigWeight->entry_age_id = $input['entry_age_id'];
		$entryConfigWeight->weight = $input['weight'];
		$entryConfigWeight->max_entry = $input['max_entry'];

		$entryConfigWeight->save();

		return $entryConfigWeight;
	}

 	public function update($id, $input)
	{
		$entryConfigWeight = $this->find($id);
		$entryConfigWeight->entry_id = $input['entry_id'];
		$entryConfigWeight->entry_age_id = $input['entry_age_id'];
		$entryConfigWeight->weight = $input['weight'];
		$entryConfigWeight->max_entry = $input['max_entry'];

		$entryConfigWeight->save();

		return $entryConfigWeight;
	}

	public function delete($id)
	{
		$entryConfigWeight = $this->find($id);

		$entryConfigWeight->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = EntryConfigWeight::select('*');

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
            ->addColumn('action', function ($entryConfigWeight) {

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="EntryConfigWeightEdit('.$entryConfigWeight->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="EntryConfigWeightDelete('.$entryConfigWeight->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['action'])
            ->make(true);

        return $data;
	}

	public function getEntryWeightByEntryId($entryId)
	{
		$weights = "";
		if(@$entryId)
		{
			$qry = EntryConfigWeight::where('entry_id', $entryId);
		}

		$weights = $qry->get();
		return $weights;
	}

	public function getEntryWeightByAgeId($ageId)
	{
		$weights = "";
		if(@$ageId)
		{
			$qry = EntryConfigWeight::where('entry_age_id', $ageId);
			$weights = @$qry->get();
		}

		return $weights;
	}

	public function getConfigWeightByEventId($eventId)
	{
		$configWeights = "";
		if(@$eventId)
		{
			$qry = EntryConfigWeight::select('uq_entry_config_weight.*')->join('uq_event_entries', 'uq_event_entries.id', '=', 'uq_entry_config_weight.entry_id')
				->where('event_id', $eventId)->orderBy('uq_event_entries.gender_code')->orderBy('uq_event_entries.name')->orderBy('uq_entry_config_weight.weight');
			$configWeights = $qry->get();
		}

		return $configWeights;
	}
}
