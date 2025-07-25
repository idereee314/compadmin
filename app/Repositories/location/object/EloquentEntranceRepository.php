<?php 

namespace location\object;

use location\object\Entrance;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentEntranceRepository implements EntranceRepository
{
    public function find($id)
    {
        return Entrance::find($id);
    }

    public function all()
    {
        return Entrance::all();
    }

    public function getEntranceByObjectLocationId($objectLocationId)
    {
        $entrances = "";
        if(@$objectLocationId)
        {
            $qry = Entrance::where('object_location_id', $objectLocationId);
            $entrances = $qry->get();
        }
        
        return $entrances;
    }
}