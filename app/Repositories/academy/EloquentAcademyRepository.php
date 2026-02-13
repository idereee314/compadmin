<?php namespace academy;

use academy\Academy;
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

class EloquentAcademyRepository implements AcademyRepository {

	public function all()
	{
		return Academy::all();
	}

	public function academyList()
	{
	    return Academy::where('type', 'academy')
	        ->orderBy('sort_order')
	        ->get();
	}

	public function allPaginate()
	{
		return Academy::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return Academy::find($id);
	}

	public function create($input)
	{
		$academy = new Academy;

		$academy->organization_id = @$input['organization_id'];
		$academy->name = $input['name'];
		$academy->name_en = $input['name_en'];
		$academy->sort_order = $input['sort_order'];
		$academy->type = $input['type'];
		$academy->founded_year = $input['founded_year'];
		$academy->sport_id = $input['sport_id'];

		$academy->save();
		return $academy;
	}

 	public function update($id, $input)
	{
		$academy = $this->find($id);
		if(array_key_exists('new_organization_id', $input))
		{
			$academy->organization_id = @$input['new_organization_id'];
		}
		$academy->name = $input['name'];
		$academy->name_en = $input['name_en'];
		$academy->sort_order = $input['sort_order'];
		$academy->type = $input['type'];
		$academy->founded_year = @$input['founded_year'];
		$academy->sport_id = @$input['sport_id'];

		$academy->save();
		return $academy;
	}

	public function delete($id)
	{
		$academy = $this->find($id);

		$academy->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = Academy::select('*')->with('organization:id,name');

        $data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                if($searchData->has('name') && $searchData->get('name') !== null)
                {
                    $qry->whereRaw('LOWER(name) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'))
						->orWhereRaw('LOWER(name_en) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'));
                }
				if($searchData->has('type') && $searchData->get('type') !== null)
                {
					$qry->where('type', $searchData->get('type'));
				}
            })
			->editColumn('type', function($qry)
			{
				return @Config::get('enums.org_type')[$qry->type];
			})
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
            ->addColumn('action', function ($academy) {
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.academy'), Config::get('permission.editable'));

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				if($permissionEdit)
				{
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="academyEdit('.$academy->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="academyDelete('.$academy->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				}
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['organization_id', 'action'])
            ->make(true);

        return $data;
	}

	public function getMemberOfAcademy($academyId)
	{
	    return DB::select("select ua.id as academy_id, ua.name as academy_name, ua.name_en as academy_name_en, ua.type as academy_type, count(um.id), 
		um.firstname as member_fname, um.lastname as member_lname, um.profile_url as member_profile_photo,
		um.gender_code as member_gender, um.country_id as member_country, um.birth as member_birthday, um.id as member_id from uq_comp.uq_academy ua
		join uq_comp.uq_event_registration uer on uer.academy_id = ua.id 
		join uq_comp.uq_member um on um.id = uer.member_id 
		where ua.type = 'academy' and ua.id = $academyId
		group by ua.id, ua.name, ua.name_en, ua.type, um.id, um.firstname, um.lastname, um.profile_photo,um.profile_photo,um.gender_code,um.country_id,um.birth");
	}

	public function getPastEventRegisteredAcademy($academyId)
	{
		return DB::select("select re.event_date, uer.event_id, re.name as event_name,rep.url FROM uq_comp.uq_academy ua 
		join uq_comp.uq_event_registration uer on uer.academy_id = ua.id 
		join uq_comp.uq_member um on um.id = uer.member_id 
		join rt_listing.rti_event re on re.id = uer.event_id 
		join rt_listing.rti_organization_event roe on roe.event_id = uer.event_id 
		join rt_listing.rti_organization ro on ro.id = roe.organization_id
		join rt_listing.rti_event_picture rep on rep.event_id = re.id 
		WHERE ua.type = 'academy' and re.event_date < now() and rep.picture_type_id = 15 and uer.academy_id = $academyId
		GROUP BY uer.event_id, re.event_date, re.name,rep.url
		order by re.event_date desc");
	}

	public function getAcademyListBySport($sport_id)
	{
	    return DB::table('uq_comp.uq_academy as academy')
	        ->select([
	            'academy.id as academy_id',
	            'academy.name as academy_name',
	            'academy.name_en as academy_name_en',
	            'academy.type as academy_type',
	            'academy.sport_id as sport_id',
	            'academy.founded_year as founded_year',
	        ])
	        ->where('academy.sport_id', (int) $sport_id)
	        ->where('academy.type', 'team')
	        ->orderBy('academy.name_en', 'asc')
	        ->get();
	}

	public function getAcademyListByEvent($event_id)
	{
		@$academyTypes = app('event\EventConfigRepository')->findByEvent($event_id)->org_types;
		
		$types = collect(explode(',', trim($academyTypes, '{}')))
			->map(fn ($t) => trim($t))
			->filter()
			->values()
			->all();
		
		$list = DB::table('uq_comp.uq_academy as academy')
			->select(['academy.id as id','academy.name as name','academy.name_en as name_en','academy.type as type','academy.sport_id as sport_id','academy.founded_year as founded_year'])
			->whereIn('academy.type', $types)
			->orderBy('academy.name_en', 'asc')
			->get();
		
		return $list;
	}

	public function findSportByAcademy($sportId, $q = null)
	{
	    $qry = Academy::query()
	        ->select(['id', 'name', 'name_en'])
	        ->where('sport_id', (int) $sportId)
			->where('type', 'academy');

	    $q = trim((string) $q);
	    if ($q !== '') {
	        $qry->where(function ($sub) use ($q) {
	            $sub->where('name_en', 'ilike', '%' . $q . '%')
	                ->orWhere('name', 'ilike', '%' . $q . '%');
	        });
	    }

	    return $qry->orderBy('sort_order', 'asc')
	        ->limit(50)
	        ->get()
	        ->map(function ($a) {
				$fullName = $a->name;
        		if ($a->name_en) {
        		    $fullName .= ' - ' . $a->name_en;
        		}
	            return [
        		    'id'   => $a->id,
        		    'text' => $fullName
        		];
	        })
	        ->toArray();
	}


	public function findMembershipAcademy($academyId)
	{
	    return Academy::from('uq_comp.uq_academy as a')
	        ->selectRaw('
	            DISTINCT ON (a.id)
	            a.*,
	            sp.id      as sport_id,
	            sp.name    as sport_name,
	            sp.name_en as sport_name_en,

	            ma.id          as membership_academy_id,
	            ma.start_date  as membership_start_date,
	            ma.end_date    as membership_end_date,
	            ma.membership_type_id,

	            mt.type        as membership_type_code,
	            mt.name        as membership_type_name,
	            mt.name_en     as membership_type_name_en,
	            mt.price       as membership_type_price,
	            mt.description as membership_type_description
	        ')
	        ->leftJoin('uq_comp.uq_sport as sp', 'sp.id', '=', DB::raw('a.sport_id::int'))
	        ->leftJoin('uq_comp.uq_membership_academy as ma', 'ma.academy_id', '=', 'a.id')
	        ->leftJoin('uq_comp.uq_membership_type as mt', 'mt.id', '=', 'ma.membership_type_id')
	        ->where('a.id', $academyId)
	        ->where('mt.type', 'academy')
	        ->orderBy('a.id')
	        ->orderByRaw('ma.updated_at desc nulls last')
	        ->orderByRaw('ma.created_at desc nulls last')
	        ->orderByDesc('ma.id')
	        ->first();
	}

	public function getDatatableMembershipAcademyList($searchData)
	{
		$qry = Academy::from('uq_comp.uq_academy as a')
		    ->selectRaw('DISTINCT ON (a.id) a.*, sp.id as sport_id, sp.name as sport_name, sp.name_en as sport_name_en, ma.id as membership_academy_id, ma.start_date as membership_start_date, ma.end_date as membership_end_date, ma.membership_type_id, mt.type as membership_type_code, mt.name as membership_type_name, mt.name_en as membership_type_name_en, mt.price as membership_type_price, mt.description as membership_type_description')
		    ->leftJoin('uq_comp.uq_sport as sp', 'sp.id', '=', DB::raw('a.sport_id::int'))
		    ->leftJoin('uq_comp.uq_membership_academy as ma', 'ma.academy_id', '=', 'a.id')
		    ->leftJoin('uq_comp.uq_membership_type as mt', 'mt.id', '=', 'ma.membership_type_id')
		    ->whereExists(function ($q) {
		        $q->select(DB::raw(1))
		          ->from('uq_comp.uq_membership_academy as ma2')
		          ->whereColumn('ma2.academy_id', 'a.id')
		          ->whereExists(function ($q2) {
		              $q2->select(DB::raw(1))
		                 ->from('uq_comp.uq_membership_type as mt2')
		                 ->whereColumn('mt2.id', 'ma2.membership_type_id')
		                 ->where('mt2.type', 'academy');
		          });
		    })
		    ->orderBy('a.id')
		    ->orderByRaw('ma.updated_at desc nulls last')
		    ->orderByRaw('ma.created_at desc nulls last')
		    ->orderByDesc('ma.id');
			
		$data = Datatables::make($qry)
			->filter(function ($qry) use ($searchData) {
				if($searchData->has('name') && $searchData->get('name') !== null)
				{
					$qry->whereRaw('LOWER(name) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'))
						->orWhereRaw('LOWER(name_en) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'));
				}
			})
			->editColumn('created_at', function ($item) {
	            return $item->created_at;
	        })
	        ->addColumn('action', function ($item) {
	            $permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.academy'), Config::get('permission.editable'));

	            $actionHtml  = '<div class="dropdown dropdown-inline">';
	            $actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
	            $actionHtml .= '<i class="fa fa-server"></i>';
	            $actionHtml .= '</a>';
	            $actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
	            $actionHtml .= '<ul class="nav nav-hoverable flex-column">';

	            if ($permissionEdit) {
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link edit" href="#" data-membershipid="'.$item->membership_academy_id.'"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link delete" href="#" data-membershipid="'.$item->membership_academy_id.'"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
	            }

	            $actionHtml .= '</ul></div></div>';

	            return $actionHtml;
	        })
	        ->rawColumns(['action'])
	        ->make(true);

	    return $data;
	}

}