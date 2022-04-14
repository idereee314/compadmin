<?php namespace reference;

use reference\EventEntriesFee;
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

class EloquentEventEntriesFeeRepository implements EventEntriesFeeRepository {

	public function all()
	{
		return EventEntriesFee::all();
	}

	public function allPaginate()
	{
		return EventEntriesFee::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EventEntriesFee::find($id);
	}

	public function create($input)
	{
		$eventEntriesFee = new EventEntriesFee;

		$eventEntriesFee->entry_id = $input['entry_id'];
		$eventEntriesFee->end_date = $input['end_date'];
		$eventEntriesFee->entrance_fee = $input['entrance_fee'];

		$eventEntriesFee->save();

		return $eventEntriesFee;
	}

 	public function update($id, $input)
	{
		$eventEntriesFee = $this->find($id);
		$eventEntriesFee->entry_id = $input['entry_id'];
		$eventEntriesFee->end_date = $input['end_date'];
		$eventEntriesFee->entrance_fee = $input['entrance_fee'];

		$eventEntriesFee->save();

		return $eventEntriesFee;
	}

	public function delete($id)
	{
		$eventEntriesFee = $this->find($id);

		$eventEntriesFee->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = EventEntriesFee::select('*');

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
            ->addColumn('action', function ($entryConfig) {

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="entryConfigEdit('.$entryConfig->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="entryConfigDelete('.$entryConfig->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['action'])
            ->make(true);

        return $data;
	}

	public function getEntriesFeeByEventId($eventId)
	{
		$configFees = "";
		if(@$eventId)
		{
			$qry = EventEntriesFee::select('uq_event_entries_fee.*')->join('uq_event_entries', 'uq_event_entries.id', '=', 'uq_event_entries_fee.entry_id')
				->where('event_id', $eventId);
			$configFees = $qry->get();
		}

		return $configFees;
	}
}
