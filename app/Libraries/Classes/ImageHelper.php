<?php

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;

class ImageHelper 
{
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

	public static function uploadImage64($image) 
	{
		$info = pathinfo(storage_path().$image->getRealPath());
		$ext = $info['extension'];

		return "data:image/".$ext.";base64,".base64_encode(file_get_contents($image->getRealPath()));
	}

	public static function base64_to_img($base64_string, $output_file) {
			
		$ifp = fopen($output_file, "wb"); 
	
		$data = explode(',', $base64_string);

		fwrite($ifp, base64_decode($data[0])); 
		fclose($ifp); 
	}

}