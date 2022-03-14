<?php

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;

class ImageHelper 
{

	
	// public static function getImageDir($imageUrl, $imageDir)
	// {
		
	// 	$fileUploadDir = Config::get('smart.upload_image_dir')[$imageDir];

	// 	$info = pathinfo(storage_path().$image->getRealPath());
	// 	$ext = $info['extension'];

	// 	return "data:image/".$ext.";base64,".base64_encode(file_get_contents($image->getRealPath()));
	// }

	// public static function uploadImage64($image) 
	// {
	// 	$info = pathinfo(storage_path().$image->getRealPath());
	// 	$ext = $info['extension'];

	// 	return "data:image/".$ext.";base64,".base64_encode(file_get_contents($image->getRealPath()));
	// }

	public static function UploadedImageDir($image, $image_url, $imageDir, $imagePath)
	{
		$extension = $image->getClientOriginalExtension();

		$filename = $image->getClientOriginalName();
		
		$uploadSuccess = $image->move($fileUploadDir.$fileUploadedUrl, $filename);

		if($uploadSuccess)
		{
			$imageUrl = $fileUploadedUrl.'/'.$filename;
		}

		return $imageUrl;
	}

}