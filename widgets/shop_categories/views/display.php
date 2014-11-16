{{ if categories }}
    <ul>
	{{ categories }} 
            <li><a href='{{url:site}}shop/categories/category/{{slug}}'>{{name}}</a></li>
	{{ /categories }}
    </ul>
{{ else }}
    <h4><?php echo lang('shop:messages:categories:no_categories'); ?></h4>
{{ endif }} 
