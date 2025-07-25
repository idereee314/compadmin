<?php 

namespace location\reference;

use location\reference\EntryType;

use Datatables;
use Session;
use Config;
use \DB;

class EloquentEntryTypeRepository implements EntryTypeRepository
{
    public function find($id)
    {
        return EntryType::find($id);
    }

    public function all()
    {
        return EntryType::all();
    }

    public function byParent()
    {
        $levels = DB::select("
            WITH RECURSIVE cte AS (
                SELECT code, LPAD('-', '0', '-')||description as description, parent_code, 1 AS level, array[code] AS path
                FROM rt_listing.rtc_entry_type
                where parent_code is null
            
                UNION  ALL
                SELECT lt.code, LPAD('-', level, '-')||' '||lt.description as description, lt.parent_code, c.level + 1, c.path || lt.code
                FROM   cte c
                JOIN   rt_listing.rtc_entry_type lt ON lt.parent_code = c.code
            )
            SELECT *
            FROM  cte
            order by cte.path");

        return $levels;
    }
}