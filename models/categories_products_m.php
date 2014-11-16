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
class Categories_products_m extends MY_Model
{


	public $_table = 'shop_categories_products';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_by_product($product_id)
	{
		return $this->where('product_id',$product_id)->get_all();
	}

	public function prepare_results_for_admin_tab($results)
	{

		$return_val = array();

		foreach($results as $result)
		{
			$return_val[$result->category_id] = $result->id;
		}

		return $return_val;

	}

	//this function duplicates all the records for original product id
	//with a new product id and the same category assignments
	public function product_duplicated($original_product_id,$new_product_id)
	{
		//fetch all rows where prod id = $or_id
		$original_product_cats = $this->where('product_id',$original_product_id)->get_all();

		foreach($original_product_cats AS $linkage)
		{
			//create the input
			$to_insert = array(
					'product_id' => $new_product_id ,
					'category_id' => $linkage->category_id,
			);

			//Add record
			$this->insert($to_insert); //returns id

		}

		return TRUE;

	}

	public function delete_by_product( $deleted_product_id )
	{
		return $this->delete_by('product_id',$deleted_product_id);
	}

	public function count_products_in_category($cat_id)
	{
		return $this->count_by( array('category_id'=>$cat_id) );
	}	

	private function rand_in()
	{
		/*
		$count = $get->limit(4);
		$items = $get->get($count);

		$random_number = rand(0,4);
		return $items[$random_number];*/
	}


	/**
	 * This should solve a whole bunch of problems
	 */
	public function get_products_by_category($category_id,$limit=NULL,$offset=0)
	{
		$this->prep_prod_count_query($category_id);

		if($limit !=NULL)
			$this->db->limit($limit);

		return $this->db->offset($offset)->get()->result();
	}

	public function get_products_by_category_count($category_id)
	{
		$this->prep_prod_count_query($category_id);
		return $this->db->count_all_results();
		/*
		return $this->db
				->select('*')
				->from('shop_products')
				->join('shop_categories_products', 'shop_categories_products.product_id = shop_products.id', 'left')
				->where('shop_categories_products.category_id',$category_id)
				->where('shop_products.public',1)
				->where('shop_products.deleted',NULL)
				->count_all_results();;*/
	}

	private function prep_prod_count_query($category_id)
	{
		$this->db
				->select('shop_products.*')
				->from('shop_products')
				->join('shop_categories_products', 'shop_categories_products.product_id = shop_products.id', 'left')
				->where('shop_categories_products.category_id',$category_id)
				->where('shop_products.public',1)
				->where('shop_products.deleted',NULL);
	}



	/*
	public function get_products_by_category_count($slug, $offset=0, $limit=100)
	{
		$products = array();

		$this->load->model('shop_categories/categories_m');
		$this->load->model('shop_categories/shop_products_has_categories_m');

		if($cat_id = $this->categories_m->get_by_slug($slug))
		{
			//now find all products assigned to this category
			return $this->shop_products_has_categories_m
														->where('id',$cate_id)
														->limit($limit)
														->offset($offset)
														->count_all();

		}

		//not found
		return 0;
	}


	public function get_products_by_category_slug($slug, $offset=0, $limit=100)
	{
		$products = array();

		$this->load->model('shop_categories/categories_m');
		$this->load->model('shop_categories/shop_products_has_categories_m');

		if($cat_id = $this->categories_m->get_by_slug($slug))
		{
			//now find all products assigned to this category
			$prod_list = $this->shop_products_has_categories_m
															->where('id',$cate_id)
															->limit($limit)
															->offset($offset)
															->get_all();

			if($prod_list)
			{
				$this->load->model('shop/shop_products_front_m');
				foreach($prod_list as $prod)
				{
					$products[] = $this->shop_products_front_m->get($prod->product_id);
				}
			}

		}

		return $products;
	}
	*/

}