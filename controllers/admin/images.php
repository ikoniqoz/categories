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
class Images extends Admin_Controller
{
	/*
	 * @var string
	 */
	//protected $section = 'products';
	private $data;


	public function __construct()
	{
		parent::__construct();

		$this->load->model('shop_categories/categories_admin_m');
		$this->load->model('shop_categories/categories_images_m');
		$this->lang->load('shop_categories');
		$this->load->library('shop_categories/image_library');
	}

	public function index(){}


	/**
	 * Upload images from the images tab
	 * site_url/admin/shop_images/images/upload/xxx
	 *
	 * @return [INT] [ID of the image uploaded]
	 */
	public function upload($category_id)
	{

		$this->image_library->sanitize_category($category_id);

		// Load libs
		$this->load->library('files/files');
		$this->load->model('files/file_folders_m');



		$folder_id = (int) Settings::get('shop_category_images');


		$exists = $this->file_folders_m->exists($folder_id);


		if( ! $exists )
		{

			// Get the first available file folder that suits our needs
			// This most likly will be a previously setup folder
			if( $this->categories_images_m->get_available_file_folders() )
			{
				$folder_id_a = $this->categories_images_m->get_available_file_folders();
				$folder_id = $folder_id_a->id;
			}
			else
			{
				$folder_id = $this->categories_images_m->create_file_folder();
			}

			//Assign the folder id
			Settings::set('shop_category_images',$folder_id);
		}




		//upload each image
		foreach($_FILES as $key => $_file)
		{
			//uploads images to files module
			$upload = Files::upload($folder_id, $_file['name'], $key );

			// Get the Image ID
	    	$file_id = $upload['data']['id'];


	    	// Assign the image to this product
	    	// Add/update the category row with te file id
			$this->categories_admin_m->assign_image($category_id,$file_id);

			//only handle 1 at  a time, the uploader will create multiple ques
			echo json_encode(
					array(
						'status' =>'completed',
						'file_id' => $file_id,
						)
					);die;
		}
	}


	/**
	 * removes a image from a product (ref) only
	 *
	 *
	 */
	public function remove($category_id, $file_id)
	{
		$return_array = $this->getAjaxReturnObject();

		//remove from cat
		$this->categories_admin_m->clear_image($category_id);

		$this->load->library('files/files');
		$ar = Files::delete_file($file_id);

		$return_array['status'] = 'success';


		$this->sendAjaxReturnObject($return_array);
	}



	private function getAjaxReturnObject()
	{
		$ret_array = array();
		$ret_array['status'] = 'error';
		$ret_array['message'] = '';

		return $ret_array;
	}

	private function sendAjaxReturnObject($array_object)
	{
		echo json_encode($array_object);die;
	}


}