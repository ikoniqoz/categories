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
class Categories_images_m extends MY_Model {


	public $_table = 'shop_categories_images';

	public function __construct()
	{
		parent::__construct();
	}


	public function create_file_folder()
	{

		$to_insert = array(
				'parent_id' => 0,
				'slug' => 'shop_category_images', //generate_slug()
				'name' => 'CategoryImages',
				'location' => 'local',
				'remote_container' => '',
				'date_added' => now(),
				'sort' => now(), //will implement the ordering in later version
				'hidden' => 1,
		);


		return $this->db->insert('file_folders',$to_insert); //returns id
	}


	public function get_available_file_folders()
	{
		return  $this->db->where('parent_id',0)->where('slug','shop_category_images')->where('name','CategoryImages')->where('hidden',1)->get('file_folders')->row();
	}


}