<?php
class lightbox
{
	/**
	 * Function that removes double-quotes so they don't interfere with the HTML.
	 */
	function cleanup($s = null)
	{
		if (!$s) return false;
		else
		{
			return str_replace('"', '', $s);
		}
	}
 
	/**
	 * Function that returns the correctly sized photo URL.
	 */
	function photo($url, $size)
	{
		$url = explode('/', $url);
		$photo = array_pop($url);
 
		switch($size)
		{
			case 'square':
				$r = preg_replace('/(_(s|t|m|b))?\./i', '_s.', $photo);
				break;
			case 'thumb':
				$r = preg_replace('/(_(s|t|m|b))?\./i', '_t.', $photo);
				break;
			case 'small':
				$r = preg_replace('/(_(s|t|m|b))?\./i', '_m.', $photo);
				break;
			case 'large':
				$r = preg_replace('/(_(s|t|m|b))?\./i', '_b.', $photo);
				break;
			default: // Medium
				$r = preg_replace('/(_(s|t|m|b))?\./i', '.', $photo);
				break;
		}
 
		$url[] = $r;
		return implode('/', $url);
	}
 
	/**
	 * Function that looks through the description and finds the first image.
	 */
	function find_photo($data)
	{
		preg_match_all('/<img src="([^"]*)"([^>]*)>/i', $data, $m);
		return $m[1][0];
	}
}
?>