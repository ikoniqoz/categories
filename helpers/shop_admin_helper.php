<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
/**
 * NitroCart	NitroCart.net - A full featured shopping cart system for PyroCMS
 *
 * @author		Salvatore Bordonaro
 * @version		2.2.0.2050
 * @website		http://nitrocart.net
 *           	http://www.inspiredgroup.com.au
 *
 * @system		PyroCMS 2.2.x
 *
 */
if (!function_exists('HelperGet_category_name'))
{

	//make sure the slug is valid
	function HelperGet_category_name($id)
	{

		$ci =& get_instance();

		$ci->load->model('shop_categories/categories_admin_m','categories_admin_m');


		$cat = $ci->categories_admin_m->get($id);

		return $cat->name;

	}
}

if (!function_exists('category_get'))
{

	//make sure the slug is valid
	function category_get($id,$parent = FALSE)
	{
		$ci =& get_instance();
		$ci->load->model('shop_categories/categories_admin_m','categories_admin_m');
		$cat = $ci->categories_admin_m->get($id);
		return $cat;
	}
}

if (!function_exists('HelperGet_category_has_parent'))
{

	//make sure the slug is valid
	function HelperGet_category_has_parent($id)
	{
		$ci =& get_instance();
		$ci->load->model('shop_categories/categories_admin_m','categories_admin_m');
		$cat = $ci->categories_admin_m->get($id);
		return ($cat->parent_id >0)?TRUE:FALSE;

	}
}

if (!function_exists('CategoryHelper_get_top_most'))
{

	//make sure the slug is valid
	function CategoryHelper_get_top_most($id)
	{

		$ci =& get_instance();

		$ci->load->model('shop_categories/categories_admin_m','categories_admin_m');

		$cat = $ci->categories_admin_m->get_top_most($id);


	}
}

if (!function_exists('CategoryHelper_category_image'))
{

	//make sure the slug is valid
	function CategoryHelper_category_image($category)
	{
		if($category->file_id != NULL)
		{
			return "<img src='files/thumb/{$category->file_id}/50/50' alt='{$category->name}' />";
		}

		return '<div class="img_noimg"></div>';

	}
}