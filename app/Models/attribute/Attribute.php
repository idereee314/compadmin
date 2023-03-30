<?php namespace attribute;

use Eloquent;
use Illuminate\Support\Facades\Auth;
use Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

use OwenIt\Auditing\Contracts\Auditable;

class Attribute extends Eloquent implements Auditable { 

    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

	protected $table = 'uq_attribute';
	protected $primaryKey = 'id';

    protected $appends = ['values'];

    //protected $hidden = ['id', 'pivot', 'attributeLovvalues'];

	public static function rules($id){
		return array(
			'code' => 'required|regex:/^[a-z0-9_]+$/u|unique:ref_attribute,code,'.$id.',id',
			'name' => 'required',
		);
	}

    public function getValuesAttribute()
    {
        $values = "";
        if($this->data_type == @Config::get('smart.attribute_data_type')['lov'])
        {
            $values = $this->attributeLovvalues()->select('code', 'name')->get();
        }
		else if($this->data_type == @Config::get('smart.attribute_data_type')['table'])
        {
            $model = new \attribute\TableValue;
			$model->setTable(@$this->table_name);
            $values = $model->select('id', 'code', 'name')->whereNull('parent_id')->get()->load('children:id,code,name,parent_id');
        }

        return $values;
    }

    public function attributeLovvalues()
    {
        return $this->hasMany('attribute\AttributeLovValue', 'attribute_id');
    }

    public function memberAttribute()
    {
        return $this->hasMany('attribute\MemberAttribute', 'attribute_id');
    }

	public static function boot()
    {
        parent::boot();   
        static::creating(function($item)
        {
            $item->created_at = Carbon\Carbon::now()->toDateTimeString();
            $item->created_by = @Auth::user()->user_id;
        });
		static::updating(function($item)
        {
			$item->updated_at = Carbon\Carbon::now()->toDateTimeString();
            $item->updated_by = @Auth::user()->user_id;
        });
        static::deleting(function($item)
        {
            $item->deleted_at = Carbon\Carbon::now()->toDateTimeString();
            $item->deleted_by = @Auth::user()->user_id;

            $item->measures()->detach();
		});
    } 
}