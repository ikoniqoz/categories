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
class Module_Shop_Categories extends Module
{

	/**
	 *
	 * @var string
	 */
	public $version = '2.2.5';

	public $mod_details = array(
			      'name'=> 'Categories', 
			      'namespace'=>'shop_categories',
			      'product-tab'=> TRUE, 
			      'prod_tab_order'=> 4, 
			      'cart'=> FALSE,
			      'has_admin'=> TRUE,
			      'routes'=>
			      		array(
			      				array(
			      						'name'	=> 'Categories List',
			      						'uri'	=> '/categories(/:num)?',
			      						'dest'	=> 'shop_categories/categories/index$1'
			      					 ),
			      				array(
			      						'name'	=> 'Category [Detail]',
			      						'uri'	=>'/categories/category(/:any)?',
			      						'dest'	=>'shop_categories/categories/category$1'
			      					 ),
			      				array(
			      						'name'	=> 'Products by Category',
			      						'uri'	=>'/categories/products(/:any)?',
			      						'dest'	=>'shop_categories/categories/products$1'
			      					 ),
			      			),

				);



	//List of tables used
	protected $module_tables = array(

			'shop_categories' => array(
				'id' 			=> array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'auto_increment' => TRUE, 'primary' => TRUE),
				'name' 			=> array('type' => 'VARCHAR', 'constraint' => '100'),
				'slug' 			=> array('type' => 'VARCHAR', 'constraint' => '100', 'unique' => TRUE, 'key' => true),
				'description' 	=> array('type' => 'TEXT'),
				'file_id' 		=> array('type' => 'CHAR', 'constraint' => '15', 'null' => TRUE, 'default' => NULL),
				'parent_id' 	=> array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'default' => 0), /*structure for heirachial but not by default*/
				'order' 		=> array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'default' => 0),
				'hidden'		=> array('type' => 'INT', 'constraint' => '1'	, 'unsigned' => TRUE, 'null' => TRUE, 'default' => 0),
                'created_by'    => array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE, 'default' => 0),
                'created'       => array('type' => 'DATETIME', 'null' => TRUE, 'default' => NULL),
                'updated'       => array('type' => 'DATETIME', 'null' => TRUE, 'default' => NULL),
			),
			'shop_categories_products' 	=> array(
				'id' 					=> array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'auto_increment' => TRUE, 'primary' => TRUE),
				'product_id' 			=> array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'default' => 0),
				'category_id' 			=> array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'default' => 0),
				//no need for delete field, we never delete this row, even if prod deleted, we want all the data
			),
	);




	public function __construct()
	{
		$this->load->library('shop/enums');
		$this->ci = get_instance();
	}


	/**
	 * info()
	 * @description: Creates 2 arrays to diplay for the module naviagtion
	 *			   One array is returned based on the user selection in the settings
	 *
	 */
	public function info()
	{

		$info =  array(
			'name' => array(
				'en' => 'NitroCart Categories',
			),
			'description' => array(
				'en' => 'NitroCart <i>A full featured shopping cart system for PyroCMS!</i>',
			),
			'skip_xss' => FALSE,
			'frontend' => TRUE,
			'backend' => TRUE,
			'menu' => FALSE,
			'author' => 'Salvatore Bordonaro',
            'roles' => array(
            	'admin_manage',
	            'admin_categories',
            ),
			'sections' => array()
		);


        $this->load->library('shop/nitrocartcore_library');
        $info['sections'] = $this->nitrocartcore_library->get_common_sections_menu();

		$info['sections']['categories'] = array(
			'name' => 'shop:admin:categories',
			'uri' => 'admin/shop_categories/categories',
            'shortcuts' => array( array('name' => 'shop_categories:create', 'uri' => 'admin/shop_categories/categories/create/','class' => 'add' ) ),
		);

		return $info;
	}


	/*
	 * The menu is handled by the main SHOP module
	 * Not needed here
	 */
    public function admin_menu(&$menu)
    {
    	 //$menu['lang:shop:admin:shop_admin']['Categories'] 		= 'admin/shop_categories/categories';
    }



	public function install()
	{

        if ( CMS_VERSION < '2.2.0' ) {
            return FALSE;
        }

		if(!$this->isRequiredInstalled())
		{
			return FALSE;
		}

		// Install tables
		$tables_installed = $this->install_tables( $this->module_tables );

		// if the tables installed, now time to register this sub-module with
		if( $tables_installed  )
		{

				//menu
		        $data = array();
		        $data[] = array(
		            'label'         => 'Categories',
		            'uri'           => 'admin/shop_categories/categories',
		            'menu'          => 'lang:shop:admin:shop_admin',
		            'module'        => 'categories',
		            'order'         => 38,
		            );
		        $this->db->insert_batch('shop_admin_menu', $data);



			if($this->install_settings())
			{
				Events::trigger("SHOPEVT_RegisterModule", $this->mod_details);
			}

			return TRUE;
		}

		return FALSE;
	}


	/*
	 */
	public function uninstall()
	{

		foreach($this->module_tables as $table_name => $table_data)
		{
			$this->dbforge->drop_table($table_name);
		}

		// Remove All settings for this module
		$this->db->delete('settings', array('module' => 'shop_categories'));

		$this->db->delete('settings', array('slug' => 'shop_cat_layout'));


        $this->db->where('module','categories')->delete('shop_admin_menu');

		//Remove categories from the core module DB
		Events::trigger("SHOPEVT_DeRegisterModule", $this->mod_details);

		return TRUE;
	}



	/*
	 */
	public function upgrade($old_version)
	{

		switch ($old_version)
		{
			case '1.0.1':
				break;
			case '2.2.1':
				break;
			default:
				break;
		}

		return TRUE;
	}


	public function help()
	{
		return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
	}



	private function init_templates()
	{
		 return TRUE;
	}

	private function install_settings()
	{

		$settings = array(

			'shop_cat_layout' => array( /*distribution location ISO 2 letter country code*//*http://www.iso.org/iso/country_codes.htm*/
				'title' => 'Categories Layout File',
				'description' => 'Select which prefered layout file to use on Categories module. If the layout does not exist, it will default to the pyro default.html',
				'type' => 'select',
				'default' => 'shop.html',
				'value' =>  'shop.html',
				'options' => 'default.html=default.html|shop.html=shop.html|shop_categories.html=shop_categories.html',
				'is_required' => TRUE,
				'is_gui' => TRUE,
				'module' => 'shop', //dont use shop - it will expose it and we need this protected
				'order' => 100
			),
		);

		foreach ($settings as $slug => $setting)
		{
			//set the settings name
			$setting['slug'] = $slug;

			if (!$this->db->insert('settings', $setting))
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	public function isRequiredInstalled()
	{

		$this->ci->load->model('module/module_m');
		$module_core = $this->ci->module_m->get_by('slug', 'shop' );

    	if( $module_core && $module_core->installed == TRUE)
    	{
    		$module = $this->ci->module_m->get_by('slug', 'shop' );
    		if( $module && $module->installed == TRUE)
    		{
				//we can now install this shop module
				return TRUE;
			}
    	}

    	return FALSE;
	}


}
/* End of file details.php */