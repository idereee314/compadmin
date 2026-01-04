<?php 

namespace location\reference;

use location\reference\ObjectType;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentObjectTypeRepository implements ObjectTypeRepository
{
    public function find($id)
    {
        return ObjectType::find($id);
    }

    public function all()
    {
        return ObjectType::all();
    }

    public function byParent()
    {
        $levels = DB::select("
            WITH RECURSIVE cte AS (
                SELECT id, LPAD('-', '0', '-')||description as description, parent_id, 1 AS level, array[id] AS path
                FROM   rt_listing.rtc_object_type
                where parent_id is null
            
                UNION  ALL
                SELECT lt.id, LPAD('-', level, '-')||' '||lt.description as description, lt.parent_id, c.level + 1, c.path || lt.id
                FROM   cte c
                JOIN   rt_listing.rtc_object_type lt ON lt.parent_id = c.id
            )
            SELECT *
            FROM  cte
            order by cte.path");

        return $levels;
    }
}