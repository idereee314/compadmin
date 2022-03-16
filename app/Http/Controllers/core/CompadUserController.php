<?php

namespace core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Input;
use Illuminate\Support\Facades\Validator;

//Repositories
use core\CompadUserRepositoryInterface as CompadUser;

//Models
use App\Models\core\CompadUser as CompadUserModel;

use \Auth as Auth;
use Config;

class CompadUserController extends Controller
{
    public $restful = true;

    public function __construct(CompadUser $compadUser)
    {
        $this->view_path = 'core.user';
        $this->compadUser = $compadUser;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->view_path.'.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->view_path.'.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDatatableList(Request $request)
    {
        return $this->compadUser->getDatatableList($request);
    }

}
