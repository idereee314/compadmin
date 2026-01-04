<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

use Session as Session;

class SmartHelper
{

	public static function getLoggedName()
	{
        return Auth::user()->firstname;
	}

	public static function checkRedis()
	{
		$status = false;
		try
		{
            $redis=Redis::connect(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', 6379));

			Session::put('redisCheck', 1);
			$status = true;
		}
		catch(\Predis\Connection\ConnectionException $e)
		{
            Session::put('redisCheck', 0);
		}

		return $status;
	}

	public static function removeUserFromRedis($userId)
	{
        $namespace = 'users:'.$userId;

        Redis::DEL($namespace);
	}

	public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 0) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 0) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 0) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
	}
}
