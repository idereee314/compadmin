<?php namespace reference;

use reference\EventEntries;
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

class EloquentEventEntriesRepository implements EventEntriesRepository {

	public function all()
	{
		return EventEntries::all();
	}

	public function allPaginate()
	{
		return EventEntries::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EventEntries::find($id);
	}

	public function create($input)
	{
		$eventEntries = new EventEntries;
		$eventEntries->event_id = $input['event_id'];
		$eventEntries->name = $input['name'];
		$eventEntries->name_en = $input['name_en'];
		$eventEntries->gender_code = $input['gender_code'];
		$eventEntries->entrance_fee = $input['entrance_fee'];

		$eventEntries->save();
		return $eventEntries;
	}

 	public function update($id, $input)
	{
		$eventEntries = $this->find($id);
		$eventEntries->event_id = $input['event_id'];
		$eventEntries->name = $input['name'];
		$eventEntries->name_en = $input['name_en'];
		$eventEntries->gender_code = $input['gender_code'];
		$eventEntries->entrance_fee = $input['entrance_fee'];

		$eventEntries->save();
		return $eventEntries;
	}

	public function delete($id)
	{
		$eventEntries = $this->find($id);

		$eventEntries->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = EventEntries::select('*');

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
            ->addColumn('action', function ($eventEntries) {

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="EventEntriesEdit('.$eventEntries->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="EventEntriesDelete('.$eventEntries->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['action'])
            ->make(true);

        return $data;
	}

	public function getEntryByEventId($eventId)
	{
		$entries = "";
		if(@$eventId)
		{
			$qry = EventEntries::where('event_id', $eventId);
			$entries = $qry->get();

			$map = $entries->map(function($items){
				$items->id = $items->id;
				$items->name = $items->name.' - '.@Config::get('enums.gender_code')[$items->gender_code];
				return $items;
			});
		}

		return $map;
	}
}
