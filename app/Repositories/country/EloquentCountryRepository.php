<?php namespace country;

use country\Country;
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

class EloquentCountryRepository implements CountryRepository {

	public function all()
	{
		return Country::all();
	}

	public function allPaginate()
	{
		return Country::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return Country::find($id);
	}

	public function create($input)
	{
		$country = new Country;

		$country->country_id = @$input['country_id'];
		$country->name = $input['name'];
		$country->name_en = $input['name_en'];
		$country->sort_order = $input['sort_order'];

		$country->save();
		return $country;
	}

 	public function update($id, $input)
	{
		$country = $this->find($id);
		if(array_key_exists('new_country_id', $input))
		{
			$country->country_id = @$input['new_country_id'];
		}
		$country->name = $input['name'];
		$country->name_en = $input['name_en'];
		$country->sort_order = $input['sort_order'];

		$country->save();
		return $country;
	}

	public function delete($id)
	{
		$country = $this->find($id);

		$country->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = Country::select('*')->with('country:id,name');

        $data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                if($searchData->has('name') && $searchData->get('name') !== null)
                {
                    $qry->whereRaw('LOWER(name) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'))
						->orWhereRaw('LOWER(name_en) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'));
                }
            })
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
            ->addColumn('action', function ($country) {
				// $permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.country'), Config::get('permission.editable'));

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				if($permissionEdit)
				{
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="countryEdit('.$country->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="countryDelete('.$country->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				}
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['country_id', 'action'])
            ->make(true);

        return $data;
	}
}
