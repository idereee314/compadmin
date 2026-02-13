<?php namespace event;

use event\EventDivision;
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

class EloquentEventDivisionRepository implements EventDivisionRepository {

	public function all()
	{
		return EventDivision::all();
	}

	public function allPaginate()
	{
		return EventDivision::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EventDivision::find($id);
	}

	public function create($input)
	{
		$eventDivision = new EventDivision;
		$eventDivision->member_id = $input['member_id'];
		

		$eventDivision->save();
		return $eventDivision;
	}

 	public function update($id, $input)
	{
		$eventDivision = $this->find($id);
		
		$eventDivision->event_id = $input['event_id'];
		$eventDivision->belt_id = $input['belt_id'];
		$eventDivision->weight_id = $input['weight_id'];

    	$eventDivision->is_finish   = !empty($input['is_finish']) ? 1 : 0;
    	$eventDivision->medal_given = !empty($input['medal_given']) ? 1 : 0;

		$eventDivision->save();
		return $eventDivision;
	}

	public function delete($id)
	{
		$eventDivision = $this->find($id); 

		$eventDivision->delete();
	}

	public function getDatatableList($searchData, $eventId)
    {
		$qry = EventDivision::query()
    		->from('uq_comp.uq_event_division as ed')
    		->leftJoin('uq_comp.uq_entry_config_belt as b', 'b.id', '=', 'ed.belt_id')
    		->leftJoin('uq_comp.uq_entry_config_weight as w', 'w.id', '=', 'ed.weight_id')
    		->leftJoin('uq_comp.uq_event_entries as e', 'e.id', '=', 'w.entry_id')
    		->leftJoin('uq_comp.uq_entry_config_age as a', 'a.id', '=', 'w.entry_age_id')
    		->select(['ed.*', 'b.name as belt_name', 'e.name as entry_name', 'e.gender_code as gender_code', 'w.weight as weight_value', 'a.start_age', 'a.end_age'])
			->orderBy('ed.is_finish', 'desc')
			->orderBy('a.start_age', 'asc')
    		->orderBy('a.end_age', 'asc')
    		->orderBy('b.name', 'asc')
    		->orderBy('w.weight', 'asc');

		if (!empty($eventId)) {
    	    $qry->where('ed.event_id', (int)$eventId);
    	}

		$data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                if($searchData->has('entry') && $searchData->get('entry') !== null)
                {
					$qry->where('uq_event_registration.entry_id', $searchData->get('entry'));
				}
				if($searchData->has('entryBelt') && $searchData->get('entryBelt') !== null)
                {
					$qry->where('uq_event_registration.entry_belt_id', $searchData->get('entryBelt'));
				}

				if($searchData->has('entryWeight') && $searchData->get('entryWeight') !== null)
                {
					$qry->where('uq_event_registration.entry_weight_id', $searchData->get('entryWeight'));
				}
            })
			->setRowClass(function ($item) {
			    if (!empty($item->medal_given)) {
			        return 'bg-light-success';
			    }

			    if (!empty($item->is_finish)) {
			        return 'bg-light-danger';
			    }

			    return '';
			})
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
			->addColumn('gender', function($qry){
				return Config::get('enums.gender_code')[$qry->configWeight->entry->gender_code];
			})
			->editColumn('is_finish', function($qry){
				return Config::get('enums.boolean_type')[$qry->is_finish];
			})
			->orderColumn('is_finish', function ($query, $order) {
    		    $query->orderBy('ed.is_finish', $order);   // эсвэл is_finish гэж alias бол тэрийг
    		})
			->editColumn('medal_given', function($qry){
				return Config::get('enums.boolean_type')[$qry->medal_given];
			})
			->addColumn('belt', function($qry){
				return $qry->configBelt->name;
			})
            ->addColumn('action', function ($qry) {
				// $permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.award'), Config::get('permission.editable'));
				$actionHtml = "";
				$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3 edit" href="javascript:;" data-divisionid="'.$qry->id.'" title="'.trans('display.general_status_change').'"><i class="fas fa-tachometer-alt"></i></a>';
				return $actionHtml;
            })->rawColumns(['action'])
            ->make(true);

			return $data;
		
	}
}
