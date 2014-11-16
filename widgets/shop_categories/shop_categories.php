<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
/*
 * SHOP for PyroCMS
 * 
 * Copyright (c) 2013, Salvatore Bordonaro
 * All rights reserved.
 *
 * Author: Salvatore Bordonaro
 * Version: 1.0.0.051
 *
 *
 *
 * 
 * See Full license details on the License.txt file
 */
 
/**
 * SHOP			A full featured shopping cart system for PyroCMS
 *
 * @author		Salvatore Bordonaro
 * @version		1.0.0.051
 * @website		http://www.inspiredgroup.com.au/
 * @system		PyroCMS 2.1.x
 *
 */
class Widget_Shop_categories extends Widgets 
{

	public $title = array(
		'en' => 'Shop - Categories',
	);
	public $description = array(
		'en' => 'Display a list of categories in your shop',
	);
	public $author = 'Salvatore Bordonaro';
	public $website = 'http://inspiredgroup.com.au/';
	public $version = '1.1';
	public $fields = array(
		array(
			'field' => 'order',
			'label' => 'Order by',
			'rules' => 'required'
		)
	);

	public function run($options) 
	{
		$this->load->model('shop_categories/categories_m');



		switch($options['order'])
		{

			case 'order_a':
				$categories = $this->categories_m->order_by('order','asc')->get_all();
				break;
			case 'name_a':
				$categories = $this->categories_m->order_by('name','asc')->get_all();
				break;
			case 'id_a':
				$categories = $this->categories_m->order_by('id','asc')->get_all();
				break;		
			case 'order_d':
				$categories = $this->categories_m->order_by('order','desc')->get_all();
				break;
			case 'name_d':
				$categories = $this->categories_m->order_by('name','desc')->get_all();
				break;
			case 'id_d':
				$categories = $this->categories_m->order_by('id','desc')->get_all();
				break;							
			default:
				$categories = $this->categories_m->get_all();
				break;

		}
		

		return array(
			'categories' => $categories,
		);
	}



}
