<?php

namespace organization;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;
use Config;
use ImageHelper;

class OrganizationPicture extends Model
{
    protected $table = 'rti_organization_picture';
    protected $primaryKey = 'id';

    public static $rules = array(
        'picture_org_id' => 'required',
        'picture_type' => 'required',
        'croppedData' => 'required',
        'orginalData' => 'required',
    );

    public static $updateRules = array(
        'picture_type' => 'required',
        'croppedData' => 'required',
        'orginalData' => 'required',
    );
    
    public function pictureType()
    {
        return $this->belongsTo('reference\PictureType', 'picture_type_id');
    }

    public static function boot()
    {
        parent::boot();
    
        // cause a delete of a product to cascade to children so they are also deleted
        static::creating(function($picture)
        {
            $picture->created_at = Carbon\Carbon::now()->toDateTimeString();
            $picture->created_by = Auth::user()->user_id;
        });

        static::updating(function($picture)
        {
            $picture->updated_at = Carbon\Carbon::now()->toDateTimeString();
            $picture->updated_by = Auth::user()->user_id;
        });

        static::deleting(function($picture)
        {
            foreach(@Config::get('smart.organization_image_size')[@$picture->pictureType->code] as $key => $type)
            {
                /*$imagePath = @Config::get('smart.upload_image_dir')['routyweb'];

                $imagePath .= $picture->pictureType->dir_url;

                if(@$key)
                {
                    $imagePath .= "/".$key;                                   
                }
                $imagePath .= "/".$picture->url;
                
                if(file_exists($imagePath))
                {
                    unlink($imagePath);
                }*/
                $imagePath = $picture->pictureType->dir_url;

                if(@$key)
                {
                    $imagePath .= "/".$key;                                   
                }
                $imagePath .= "/".$picture->url;
                
                if (\Storage::disk('s3')->exists($imagePath)) {                
                    \Storage::disk('s3')->delete($imagePath);
                }
            }

            /*$imagePath = @Config::get('smart.upload_image_dir')['routyweb'].$picture->pictureType->dir_url."/".$picture->url;
            if(file_exists($imagePath))
            {
                unlink($imagePath);
            }*/

            $imagePath = $picture->pictureType->dir_url."/".$picture->url;

            if (\Storage::disk('s3')->exists($imagePath)) {                
                \Storage::disk('s3')->delete($imagePath);
            }
        });
    }
}
