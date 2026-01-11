<?php namespace membership;

use membership\MembershipAcademy;

use core\sessions\Sessions;
use user\User;
use event\EventRegistration;

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
use Str;

class EloquentMembershipAcademyRepository implements MembershipAcademyRepository {

	public function all()
	{
		return MembershipAcademy::all();
	}

	public function find($id)
	{
		return MembershipAcademy::find($id);
	}

	public function create($input)
	{
		$membership = new MembershipAcademy;

		$membership->academy_id = @$input['academy_id'];
		$membership->membership_type_id = $input['membership_type_id'];
		$membership->start_date = $input['start_date'];
		$membership->end_date = $input['end_date'];
		$membership->description = @$input['description'];

		$membership->save();
		return $membership;
	}

 	public function update($id, $input)
	{
		$membership = $this->find($id);
		$membership->academy_id = @$input['academy_id'];
		$membership->membership_type_id = $input['membership_type_id'];
		$membership->start_date = $input['start_date'];
		$membership->end_date = $input['end_date'];
		$membership->description = @$input['description'];

		$membership->save();
		return $membership;
	}

	public function delete($id)
	{
		$membership = $this->find($id);
		
		$membership->delete();	
	}
}
