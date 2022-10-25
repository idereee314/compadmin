<?php namespace reference;

use reference\EntryConfigAge;
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

class EloquentEntryConfigAgeRepository implements EntryConfigAgeRepository {

	public function all()
	{
		return EntryConfigAge::all();
	}

	public function allPaginate()
	{
		return EntryConfigAge::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EntryConfigAge::find($id);
	}

	public function create($input)
	{
		$entryConfigAge = new EntryConfigAge;

		$entryConfigAge->entry_id = $input['entry_id'];
		$entryConfigAge->start_age = $input['start_age'];
		$entryConfigAge->end_age = $input['end_age'];

		$entryConfigAge->save();

		return $entryConfigAge;
	}

 	public function update($id, $input)
	{
		$entryConfigAge = $this->find($id);
		$entryConfigAge->entry_id = $input['entry_id'];
		$entryConfigAge->start_age = $input['start_age'];
		$entryConfigAge->end_age = $input['end_age'];

		$entryConfigAge->save();

		return $entryConfigAge;
	}

	public function delete($id)
	{
		$entryConfigAge = $this->find($id);

		$entryConfigAge->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = EntryConfigAge::select('*');

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
            ->addColumn('action', function ($entryConfigAge) {

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="entryConfigAgeEdit('.$entryConfigAge->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="entryConfigAgeDelete('.$entryConfigAge->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['action'])
            ->make(true);

        return $data;
	}

	public function getEntryAgeByEntryId($entryId)
	{
		$ages = "";
		if(@$entryId)
		{
			$qry = EntryConfigAge::where('entry_id', $entryId);
			$ages = $qry->get();
		}

		return $ages;
	}

	public function getConfigAgeByEventId($eventId)
	{
		$configAges = "";
		if(@$eventId)
		{
			$qry = EntryConfigAge::selectRaw("uq_entry_config_age.id, start_age, end_age, CASE WHEN start_age is null THEN '-' || end_age WHEN end_age is null THEN start_age || '+' else start_age || '-' || end_age END as age, entry_id, uq_entry_config_age.created_at")
				->join('uq_event_entries', 'uq_event_entries.id', '=', 'uq_entry_config_age.entry_id')
				->where('event_id', $eventId)->orderBy('uq_event_entries.gender_code')->orderBy('uq_event_entries.name');
			$configAges = $qry->get();
		}

		return $configAges;
	}
}
