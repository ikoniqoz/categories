<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
/**
 * NitroCart    NitroCart.net - A full featured shopping cart system for PyroCMS
 *
 * @author      Salvatore Bordonaro
 * @version     2.2.0.2050
 * @website     http://nitrocart.net
 *              http://www.inspiredgroup.com.au
 *
 * @system      PyroCMS 2.2.x
 *
 */
class Image_library
{

	protected $jpeg_quality = 90;
	protected $png_quality = 9;


	public function __construct()
	{
		log_message('debug', "Class Initialized");
	}

	/**
	 * Get the CI instance into this object
	 *
	 * @param unknown_type $var
	 */
	public function __get($var)
	{
		if (isset(get_instance()->$var))
		{
			return get_instance()->$var;
		}
	}

	public function uninstall_module()
	{
		if($this->db->table_exists('shop_categories_images'))
		{
			$this->load->model('shop_categories/categories_images_m');
			$this->load->library('files/files');

			$images = $this->categories_images_m->get_all();
			foreach($images as $image)
			{
				$ar = Files::delete_file($image->file_id);
			}
		}
	}


	/**
	 * This will delete the physical image for the category
	 * @param  [type] $category_id [description]
	 * @return [type]              [description]
	 */
	public function sanitize_category($category_id)
	{
		//load model
		$this->load->model('shop_categories/categories_m');
		$this->load->library('files/files');
		$this->load->model('files/file_folders_m');

		//Get categry DB data
		$cat = $this->categories_m->get($category_id);
		if($cat)
		{
			if($cat->file_id != '')
			{
				Files::delete_file($cat->file_id);
			}
		}

		return TRUE;
	}


}