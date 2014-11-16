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
class Categories_library
{

	public function __construct()
	{

	}


	public function process( $categories = array(), $outer_el ='ul', $inner_el='li')
	{
		return $this->build($categories,$outer_el,$inner_el);
	}

	//need to add classes
	//first
	//current
	//has_children
	//parent == everything
	//last
	public function build($categories, $outer_el, $inner_el='li')
	{
		$first = TRUE;
		$last = FALSE;

		$str ='';
		if($categories)
		{
			$str = "<{$outer_el}>";


			$counter = 0;
			foreach($categories as $key => $category)
			{
				$counter++;
				$class = '';

				//var_dump(count($categories));
				//echo $key;die;

				if(count($categories) == ($counter))
				{
					$last = TRUE;
				}

				//var_dump($category['children']);die;

				if($category['children'])
				{
					$class = 'has_children';
				}

				$class .= ($category['current'])? ' current' : '' ;
				$class .= ($first)? ' first' : '' ;
				$class .= ($last)? ' last' : '' ;


				$str .= "<{$inner_el} class='{$class}'>";
				$str .= "   <a href='{{url:site}}shop/categories/products/{$category['category']->slug}'>{$category['category']->name}</a>";

				$str .= $this->build($category['children'],$outer_el,$inner_el);

				$str .= '</{$inner_el}>';

				$first = FALSE;
			}

			$str .= "</{$outer_el}>";
		}

		return $str;
	}

}
/* End of file details.php */