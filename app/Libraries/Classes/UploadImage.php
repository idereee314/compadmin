<?php namespace App\Libraries\Classes;

use Illuminate\Support\Facades\Storage;

class UploadImage
{
	public static function uploadImage($image, $fileUploadDir, $type)
	{
		$imageUrl = "";

		$extension = $image->getClientOriginalExtension();
        $originName = $image->getClientOriginalName();
        $filename = pathinfo($originName, PATHINFO_FILENAME);

		//$filename = $fileUploadDir . '_' . date('YmdHis') . '_' . uniqid() . '.'.$extension;
        $filename = $filename.'_'.time().'.'.$extension;

        //$filename = Storage::disk('editor')->put('', $image);                
        $upload_success = $image->move($_SERVER['DOCUMENT_ROOT'].'/assets/images/'.$fileUploadDir, $filename);

		if($upload_success)
		{
            if($type == "editor") {
                return $filename;
            } else {
                return 	$imageUrl = $fileUploadDir.'/'.$filename;
            }
		}
	}
}

