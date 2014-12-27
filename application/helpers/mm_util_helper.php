<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('mm_cache_config'))
{
	function mm_cache_config($expired=CACHE_MID_EXP) 
	{
		$CI =& get_instance();
		$CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		$key = 'mm_config';
		$hashed_key = md5($key);

		if ( ! $data = $CI->cache->get($hashed_key) )
		{
			include(APPPATH.'config/'.$key.'.php');
			$data = $config;
			$CI->cache->save($hashed_key, $data, $expired);	
		}		
		return $data;		
	}
}

if ( ! function_exists('base64url_encode'))
{
	function base64url_encode($data) {
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
}

if ( ! function_exists('base64url_decode'))
{
	function base64url_decode($data) {
		return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
}

/**
* Search penandatangan
* return true
*/
if ( ! function_exists('search_penandatangan'))
{
	function search_penandatangan($layer, $users_email, $array) {
		foreach($array as $key=>$val)
		{
			if( intval($val['STEP_LAYER']) == intval($layer) && $val['E_MAIL_ADDR'] == $users_email )
			{
				return TRUE;
			}
		}
	}
}
/**
* Search penandatangan nota
* return true
*/
if ( ! function_exists('search_penandatangan_nota'))
{
	function search_penandatangan_nota($layer, $users_no, $array) {
		foreach($array as $key=>$val)
		{
			if( intval($val['STEP_LAYER']) == intval($layer) && $val['EMPLOYEE_NO'] == $users_no )
			{
				return TRUE;
			}
		}
	}
}


/**
* Searches haystack for needle and 
* returns an array of the key path if 
* it is found in the (multidimensional) 
* array, FALSE otherwise.
*
* @mixed array_searchRecursive ( mixed needle, array haystack [, bool strict[, array path]] )
*/
function array_searchRecursive( $needle, $haystack, $strict=false, $path=array() )
{
	if( ! is_array($haystack) ) {
		return false;
	}
 
	foreach( $haystack as $key => $val ) {
		if( is_array($val) && $subPath = $this->array_searchRecursive($needle, $val, $strict, $path) ) {
			$path = array_merge($path, array($key), $subPath);
			return $path;
		} elseif( (!$strict && $val == $needle) || ($strict && $val === $needle) ) {
			$path[] = $key;
			return $path;
		}
	}
	
	return false;
	
} // end array_searchRecursive


/*
*	return format mysql datetime to Sunday, 29-Januari-2012 00:00:00
*/
if ( ! function_exists('id_date_complete'))
{
	function id_date_complete($str)
	{
		if($str) {
			$CI =& get_instance();		
			$monthArray = $CI->config->item('monthArray');
			$strtotime = strtotime($str);
			$month = date('m', $strtotime);
			$day = date('l', $strtotime);
			return date('d ', $strtotime).$monthArray[intval($month)-1].date(" Y", $strtotime);
		}
		else
		{
			return NULL;
		}
	}
}

/*
*	sanitize filename, url friendly
*/ 
function sanitize_filename($str, $relative_path = FALSE)
{
	$bad = array( "../", "<!--", "-->", "<", ">", "'", '"', '&', '@', '*', '$', '#', '{',
					'}', '[', ']', '(', ')', '=', ';', '?', "%20", "%22", "%3c", // <
					"%253c",	// <
					"%3e",		// >
					"%0e",		// >
					"%28",		// (
					"%29",		// )
					"%2528",	// (
					"%26",		// &
					"%24",		// $
					"%3f",		// ?
					"%3b",		// ;
					"%3d"		// =
				);

	if ( ! $relative_path)
	{
		$bad[] = './';
		$bad[] = '/';
	}

	$str = remove_invisible_characters($str, FALSE);
	return stripslashes(str_replace($bad, '', $str));
}	
	