<?php

class SecurityHelper
{
	public static function checkMenuPermissionByCode($permission)
	{
		
		$menus = Session::get('userMenus');
		if(Session::has('userMenus') && array_key_exists($permission, $menus))
		{
			return true;
		}

		return false;
	}

	public static function checkPermission($menuCode, $permissionCode)
	{
		if(Session::has('userMenus') && @Session::get('userMenus')[$menuCode] == $permissionCode)
		{
			return true;
		}

		return false;
	}
}