<?php 

namespace location\unit;

use location\unit\AimagCity;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentAimagCityRepository implements AimagCityRepository
{
    public function find($id)
    {
        return AimagCity::find($id);
    }

    public function all()
    {
        return AimagCity::all();
    }

    public function create($input)
    {
      
    }

    public function delete($id)
    {
    }

    public function update($id, $data)
    {
        
    }

    public function getDatatableList($searchData)
    {
      
    }

    public function orderByCode()
    {
        return AimagCity::select("*")->orderBy('code')->get();
    }

    public function getAimagCityByPoint($point)
    {
        return DB::select("
            select id, code, name from rt_listing.rta_aimag_city
            where ST_Contains(geometry, '$point')
        ");
    }
}