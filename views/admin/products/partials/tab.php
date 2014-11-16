
				<?php if(!(isset($userDisplayMode))):?>
					$userDisplayMode = 'edit';
				<?php endif;?>

		      <?php if(!group_has_role('shop_categories','admin_manage'))
		      {
		          $userDisplayMode = 'view';
		          echo "<fieldset><h3 style='color:#f00'>You do not have permission to manage categories.</h3></fieldset>";
		      }
		      ?>	
		      
				<fieldset>
						<h3>Category Assignments</h3>
						<h4>If the category does not exist, go over to manage categories.</h4>
						<?php if($userDisplayMode == 'edit'):?>
	            			<a class="sbtn gray glow" href="admin/shop_categories/categories/create/">Create a new category</a>
						<?php endif;?>
				</fieldset>


				<fieldset>

						<h4>Assign your product to a category by selecting the LINK button.</h4>


							<div class="input">

								<?php if(isset($modules['shop_categories']['list'])) : ?>
									<table class='category_rows'>

										<?php foreach($modules['shop_categories']['list'] as $category_id => $category) : ?>
											<tr>
												<td style='width:80%'><?php echo $category; ?></td>
												<td>
                                                    <span></span>
                                                    <span style='float:right'>

													<?php if(isset($modules['shop_categories']['assigned'])) :?>

															<?php if(isset($modules['shop_categories']['assigned'][$category_id])) :?>


																<?php if($userDisplayMode != 'edit'):?>
											            		  	Assigned
											            		<?php else:?>

																	<?php $link_id = $modules['shop_categories']['assigned'][$category_id];?>

																	<a class='button blue category_linker' href='admin/shop_categories/categories/unlink/<?php echo $id;?>/<?php echo $category_id;?>/<?php echo $link_id;?>'>Unlink</a>

																<?php endif;?>


															<?php else:?>

																<?php if($userDisplayMode == 'edit'):?>
											            			<a class='button gray category_linker' href='admin/shop_categories/categories/link/<?php echo $id;?>/<?php echo $category_id;?>'>Link</a>
											            		<?php endif;?>

															<?php endif;?>

													<?php else:?>

														<?php if($userDisplayMode == 'edit'):?>
															<a class='button gray category_linker' href='admin/shop_categories/categories/link/<?php echo $id;?>/<?php echo $category_id;?>'>Link</a>
														<?php endif;?>

													<?php endif;?>
                                                    </span>
												</td>
											</tr>
										<?php endforeach;?>

									</table>


								<?php endif;?>

							</div>


				</fieldset>


<script>

	function add_to_products_list(category_name,category_id,product_id)
	{
		var str = '';

		str += '<tr><td>' + category_name + '</td><td>';
		str += '<a class="button gray category_linker" href="admin/shop_categories/categories/link/' + product_id + '/'+ category_id + '">Link</a></td></tr>';
		$('table.category_rows tbody').append(str);

	}

	function category_enabled(button, is_linked, product_id, category_id, link_id)
	{

		var link_link = 'admin/shop_categories/categories/link/'+product_id+'/'+category_id;
		var unlink_link = 'admin/shop_categories/categories/unlink/'+product_id+'/'+category_id+'/'+link_id;

		link = ((is_linked)? unlink_link : link_link);
		buttonText = ((is_linked)? 'Unlink' : 'Link');
		classes = ((is_linked)? 'blue' :  'gray');

		button.text(buttonText);
		button.attr('href', link );
		button.attr('class','category_linker button '+classes);

	}


    $(document).on('click', '.category_linker', function(event) {

    	var button = $(this);
        var url = button.attr('href');

          $.post(url).done(function(data)
          {

              var obj = jQuery.parseJSON(data);

              if(obj.status == 'success')
              {
              		category_enabled( button , obj.is_linked, obj.product_id , obj.category_id , obj.link_id);
              }
              else
              {
      				alert('Unable to process category..');
              }

          });


          // Prevent Navigation
          event.preventDefault();

    });

    $(document).on('click', '.quickAddButton', function(event) {

    	var cat_name_in = $("input[name='cat_name']").val();
        var url = 'admin/shop_categories/categories/add_quick/<?php echo $id;?>/';

          $.post(url, { cat_name:cat_name_in}).done(function(data)
          {

              var obj = jQuery.parseJSON(data);

              if(obj.status == 'success')
              {
              		add_to_products_list(cat_name_in,obj.id, obj.product_id );
              }
              else
              {
      				alert(obj.message);
              }

          });


          // Prevent Navigation
          event.preventDefault();

    });

</script>