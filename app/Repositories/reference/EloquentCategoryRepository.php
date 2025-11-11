<?php 

namespace reference;

use reference\Category;
use reference\CategoryType;

use Datatables;
use Session;
use Config;
use \DB;
use Auth;
use Illuminate\Support\Str;

class EloquentCategoryRepository implements CategoryRepository
{
    public function find($id)
    {
        return Category::find($id);
    }

    public function all()
    {
        return Category::all();
    }

    public function create($input)
    {
        $category = new Category;
        $category->code = @$input['code'];
        $category->name = @$input['name'];
        $category->name_en = @$input['name_en'];
        $category->description = @$input['description'];
        $category->parent_id = @$input['parent_id'];
        $category->show_order = @$input['show_order'];
        $category->bg_color = @$input['bg_color'];
        $category->is_special = @$input['is_special'] ? $input['is_special'] : false;
        $category->category_type = '{'.implode(", ", $input['category_type']).'}';

        $category->save();
        $category->services()->attach(@$input['services']);

        return $category;
    }

    public function delete($id)
    {
        $category = Category::find($id);

        if ($category->cover_url) {
            if(file_exists(Config::get('smart.upload_image_dir')['routyweb'].$category->cover_url))
            {
                unlink(Config::get('smart.upload_image_dir')['routyweb'].$category->cover_url);
            }
        }

        $category->delete();
    }

    public function update($id, $data)
    {
        $category = Category::find($id);
        $category->code = @$data['code'];
        $category->name = @$data['name'];
        $category->name_en = @$data['name_en'];
        $category->description = @$data['description'];
        $category->parent_id = @$data['parent_id'];
        $category->show_order = $data['show_order'];
        $category->bg_color = @$data['bg_color'];
        $category->is_special = @$data['is_special'] ? $data['is_special'] : false;
        $category->category_type = '{'.implode(", ", @$data['category_type']).'}';

        $category->save();
        $category->services()->sync(@$data['services'], false);

        return $category;
    }

    public function getDatatableList($searchData)
    {
        $category = Category::select('*')->whereNull('parent_id')->with('children')->withCount(['organizations', 'services']);
        
        $data = Datatables::of($category)
        ->filter(function ($category) use ($searchData) {
            if($searchData->has('name') && $searchData->get('name') !== null)
            {
                $category->where(function($q) use($searchData){
                    $q->whereRaw('LOWER(name) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'))
                        ->orWhereHas('children', function($q1) use($searchData){
                            $q1->whereRaw('LOWER(name) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'));
                        });
                });
            }
            else {
                $category->whereNull('parent_id');
            }

            if($searchData->has('show_order') && $searchData->get('show_order') !== null)
            {
                $category->where('show_order', $searchData->get('show_order'));
            }

            if($searchData->has('is_active') && $searchData->get('is_active') !== null)
            {
                $category->where('is_active', $searchData->get('is_active'));
            }
        })
        ->editColumn('organizations_count', function ($category) {
            if ($category->parent_id == null) {
                @$catChilds = @$category->child()->pluck('id')->toArray();

                array_push($catChilds, $category->id);
        
                $catChildId = implode(",", $catChilds); 
        
                @$count = DB::select("
                    select count(org.id) from rti_organization org
                    inner join rti_organization_category os on org.id = os.organization_id
                    inner join rti_category cat on cat.id = os.category_id 
                    where cat.id in ($catChildId);
                ");
        
                return @$count[0]->count;
            }
            else
            {
                return @$category->organizations()->count();
            }
        })
        ->editColumn('services_count', function ($category) {
            if ($category->parent_id == null) {
                @$catChilds = @$category->child()->pluck('id')->toArray();

                array_push($catChilds, $category->id);
        
                $catChildId = implode(",", $catChilds);
        
                @$count = DB::select("
                    select count(service_id) from rt_listing.rtm_category_services where category_id in ($catChildId);
                ");
        
                return @$count[0]->count;
            }
            else
            {
                return @$category->services()->count();
            }
        })
        ->editColumn('is_active', function ($category) {
            return Config::get('smart.is_active')[$category->is_active];
        })
        ->editColumn('is_special', function ($category) {
            return Config::get('smart.is_special')[$category->is_special];
        })
        ->editColumn('cover_image', function ($category) {
            if ($category->image64 || $category->cover_url) {
                return '<button class="show-icon" data-categoryid="'.$category->id.'"><span class="glyphicon glyphicon-eye-open"></span></button>';
            }
            return " ";
        })
        ->addColumn('action', function ($category) 
        {
            $actionHtml = "";
            $actionHtml = '<div class="btn-group dropup">';
            $actionHtml .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">';
            $actionHtml .= '<i class="fa fa-server"></i>';
            $actionHtml .= '<span class="sr-only">Toggle Dropdown</span>';
            $actionHtml .= '</button>';
            $actionHtml .= '<ul class="dropdown-menu pull-right">';
            
            $actionHtml .= '<li><a href="'.route('reference.category.edit', $category->id).'">'.trans('display.general_edit').'</a></li>';

            $actionHtml .= '<li class="divider"></li>';
            
            $actionHtml .= '<li><a href="javascript:;" class="delete-category" data-categoryid="'.$category->id.'">'.trans('display.general_delete').'</a>';
                           
            $actionHtml .= '</ul>';
            $actionHtml .= '</div>';

            return $actionHtml;
        })
        ->rawColumns(['cover_image', 'action'])
        ->make(true);

        return $data;
    }

    public function getOnlyParents()
    {
        return Category::whereNull('parent_id')->get();
    }

    public function byParent($type, $hasChildren = true)
    {
        //DB::connection()->enableQueryLog();
        if($hasChildren)
        {
            $children = "
                UNION  ALL
                SELECT lt.id, LPAD('-', level, '-')||' '||lt.name as name, lt.parent_id, c.level + 1, c.path || lt.id
                FROM   cte c
                JOIN   rt_listing.rti_category lt ON lt.parent_id = c.id";
        }
       
        $levels = DB::select("
            WITH RECURSIVE cte AS (
                SELECT id, LPAD('-', '0', '-')||name as name, parent_id, 1 AS level, array[id] AS path
                FROM   rt_listing.rti_category
                where parent_id is null and '".$type."' = ANY(category_type)
                ".@$children."                
            )
            SELECT *
            FROM  cte
            order by cte.path");
            /*
            $queries = DB::getQueryLog();
            dd($queries);
            */
        return $levels;
    }

    public function getLastShowOrderByParent($catId)
    {
        if ($catId) {
            $category = $this->find($catId);
    
            $parentCategoryId = Category::find($catId)->id;
    
            $lastSubItem = DB::select("
                select cat.show_order+1 as show_order from rt_listing.rti_category cat
                inner join rt_listing.rti_category cat2 on cat.parent_id = cat2.id
                where cat.parent_id = $parentCategoryId order by show_order desc limit 1
            ");
    
            if($lastSubItem){
                
                return $lastSubItem[0]->show_order;
            }
            else
            {
                $lastSubItem = DB::select("select show_order+1 as show_order from rt_listing.rti_category where id = $parentCategoryId");
    
                return $lastSubItem[0]->show_order;
            }
        }
        else
        {
            return $this->getLastShowOrder();
        }
    }

    public function getLastShowOrder()
    {
        $lastSubItem = DB::select("select show_order+100 as show_order from rt_listing.rti_category where parent_id is null order by show_order desc limit 1");

        return $lastSubItem[0]->show_order;
    }

    public function getCategoriesByOrgId($orgId)
    {
        $categoires = "";
        if(!empty(@$orgId))
        {
            $qry = Category::whereHas('organizations', function($q) use($orgId){
                $q->where('id', $orgId);
            })->withCount(['services']);
            $categoires = $qry->orderBy('name', 'asc')->get();
        }   

        return $categoires;
    }

    public function allCategoryTypes()
    {
        return CategoryType::all();
    }

    public function findCategoriesByTypeId($typeId)
    {
        $categoires = "";
        if(!empty(@$typeId))
        {
            $qry = Category::whereRaw("'".$typeId."' = ANY(category_type)");
            $categoires = $qry->get();
        }   

        return $categoires;
    }
}