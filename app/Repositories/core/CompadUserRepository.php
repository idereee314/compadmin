<?php namespace core;

use App\Models\CompadUser;
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

class CompadUserRepository implements CompadUserRepositoryInterface {

	public function all()
	{
		return CompadUser::all();
	}

	public function allPaginate()
	{
		return CompadUser::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return CompadUser::find($id);
	}


	public function create($input)
	{
		$user = new CompadUser;

		$user->firstname = $input['firstname'];
		$user->lastname = $input['lastname'];
		$user->email = $input['email'];
		$user->username = $input['username'];
		$user->password = md5($input['password']);

		$user->save();
	}

 	public function findByEmail($email)
 	{
 		$user = CompadUser::where('email', '=', $email)->first();
 		return $user;
	}

 	public function update($id, $input)
	{
		$user = $this->find($id);
		$user->firstname = $input['firstname'];
		$user->lastname = $input['lastname'];
		$user->username = $input['username'];
		$user->email = $input['email'];

		$user->save();
	}


	public function delete($id)
	{
		$user = $this->find($id);

		$user->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = CompadUser::select('*');

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
            ->addColumn('action', function ($user) {
				$actionHtml = '<div class="btn-group dropup">';
					$actionHtml .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">';
						$actionHtml .= '<i class="fa fa-server"></i>';
						$actionHtml .= '<span class="sr-only">Toggle Dropdown</span>';
					$actionHtml .= '</button>';
					$actionHtml .= '<ul class="dropdown-menu float-right">';
					$actionHtml .= '<a href="javascript:;" class="dropdown-item user-edit" data-userid="'.$user->user_id.'">'.trans('display.general_edit').'</a>';
					$actionHtml .= '<a href="javascript:;" class="dropdown-item user-delete" data-userid="'.$user->user_id.'">'.trans('display.general_delete').'</a>';
					$actionHtml .= '<li class="divider"></li>';
					$actionHtml .= '<a href="javascript:;" class="dropdown-item change-password" data-userid="'.$user->user_id.'">'.trans('display.user_password_change').'</a>';
					$actionHtml .= '</ul>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['action'])
            ->make(true);

        return $data;
	}
}
