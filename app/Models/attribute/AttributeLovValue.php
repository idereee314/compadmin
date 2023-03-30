<?php namespace attribute;

use Eloquent;
use Illuminate\Support\Facades\Auth;
use Carbon;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
class AttributeLovValue extends Eloquent implements Auditable {

    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    
	protected $table = 'uq_attribute_lov_value';
	protected $primaryKey = 'id';
 
    protected $hidden = ['attribute_id'];

	public static function rules($id){
		return array(
            'attribute_id' => 'required',
			'name' => 'required'
		);
	}

    public function attribute()
    {
        return $this->belongsTo('attribute\Attribute', 'attribute_id');
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
  
		});
    } 
}