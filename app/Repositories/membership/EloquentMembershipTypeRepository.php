<?php namespace membership;

use membership\MembershipType;

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

class EloquentMembershipTypeRepository implements MembershipTypeRepository {
	public function all()
	{
		return MembershipType::all();
	}

	public function find($id)
	{
		return MembershipType::find($id);
	}

	public function create($input)
	{
		
	}

 	public function update($id, $input)
	{
		
	}

	public function delete($id)
	{
		
	}
}
