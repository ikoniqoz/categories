<ol>
	<li class="even">
		<label>Order by:</label>
		<?php echo form_dropdown('order', array( 
						'order_a' => 'Predefined Order - Ascending', 
						'name_a' => 'Name - Ascending', 
						'id_a' => 'ID - Ascendinf',
						'order_d' => 'Predefined Order - Descending', 
						'name_d' => 'Name - Descending', 
						'id_d' => 'ID - Descending'),
						 $options['order']

						); 
		?>
	</li>
</ol>