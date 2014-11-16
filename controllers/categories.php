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
class Categories extends Public_Controller
{

	public function __construct()
	{
		parent::__construct();
		Events::trigger('SHOPEVT_ShopPublicController');

		Settings::get('shop_open_status') OR redirect('shop/closed');

		// Retrieve some core settings

		$this->load->model('shop_categories/categories_m');
		$this->data = (object) array();

		$this->limit = Settings::get('shop_qty_perpage_limit_front');
		$this->view_mode = ($this->session->userdata('products_view_mode')) ? $this->session->userdata('products_view_mode') : 'list';
		$this->order_by =  ($this->session->userdata('products_ordering_by')) ? $this->session->userdata('products_ordering_by') : 'id';
		$this->order_by_dir =  ($this->session->userdata('products_ordering_by_order')) ? $this->session->userdata('products_ordering_by_order') : 'asc';

		$this->setLayoutForShop();

        $this->template	
        	->title(Settings::get('shop_name'))
			->set_breadcrumb('Home', '/')
			->set_breadcrumb('Shop', '/shop');

	}



	/**
	 * This displays the list of ALL products.
	 * @uri yourdomain.com/shop/products
	 */
	public function index( $offset = 0 )
	{
		//$this->limit = ;

 		$filter = array();

 		$total_items = $this->categories_m->count_by( array('parent_id' =>0, 'hidden'=>0 ));

		//  Build pagination for these items
		$pagination = create_pagination( 'shop/categories/' , $total_items, $this->limit, 3);

		//  Count total items by the given filter
		$categories = $this->categories_m->where('hidden',0 )->where('parent_id',0)->limit($pagination['limit'])->offset($pagination['offset'])->get_all();

		// finally
		$this->template
			->set_breadcrumb('Categories')
			->set('total_items', $total_items)
			->set('categories', $categories)
			->set('pagination', $pagination )
			->build('shop_categories/categories_list');
	}

	/**
	 * View the category details
	 *
	 */
	public function category( $idslug = 0 )
	{
		$method = (is_numeric($idslug))? 'id' : 'slug' ;

		$category = $this->categories_m->get_by( $method,  $idslug );


		if( ! $category )
		{
			$this->session->set_flashdata(JSONStatus::Error,'Unable to find category.');
			redirect('shop/categories');
		}

		$this->load->model('shop_categories/categories_products_m');
		//we want to know the # of product available in this category
		$category->product_count = $this->categories_products_m->count_products_in_category($category->id);


		// finally
		$this->template
			->set_breadcrumb('Categories', '/shop/categories')
			->set_breadcrumb($category->name)
			->set('category',$category)
			->build('shop_categories/category_detail');		
	}


	/**
	 * View list of products by category
	 * $idslug = category_id
	 */
	public function products($idslug = 0, $offset = 0)
	{
		$data = (object) array();

		$this->load->model('shop/products_front_m');
		$this->load->model('shop_categories/categories_products_m');

		$method = (is_numeric($idslug)) ? 'id' : 'slug' ;
		$category = $this->categories_m->get_by( $method,  $idslug );

		//if no category, redirect away from here
		$category OR redirect('shop/categories');
		$products = array();

		$pag_uri = "shop/categories/products/{$idslug}";

		//count the total products in the category
		$total_items =  $this->categories_products_m->get_products_by_category_count( $category->id );
		$pagination = create_pagination( $pag_uri , $total_items, $this->limit, 5);	

		$data->products =  $this->categories_products_m->get_products_by_category($category->id, $pagination['limit'], $pagination['offset'] );
		$data->product_count = $total_items;
		$data->pagination = $pagination;
		$data->category = $category;
		$data->viewmode = $this->view_mode;

		// finally
		$this->template
			->set_breadcrumb('Categories', '/shop/categories')
			->set_breadcrumb( $category->name )
			->set('offset',$offset)
			->set('limit',$this->limit)
			->set('message','')
			->set('view_title',$category->name)		
			->build('shop_categories/category_products_list',$data );
			//->build('shop/common/products_list',$data );

	}

	/**
	 * Overrides the layout so we use shop.html instead of shop_categories.html
	 */
	private function setLayoutForShop()
	{

		$preferred = $this->settings_m->get_by(array('slug' => 'shop_cat_layout'));

		if($this->template->layout_exists($preferred->value))
		{
			$this->template->set_layout($preferred->value);
		}
	}

}