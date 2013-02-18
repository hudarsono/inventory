<div class="span-24 last">
	<h2>Consolidate Statement</h2>
	
	
	<table>
		<tr>
			<th>Brand</th>
			<th>Model</th>
			<th>Colour</th>
			<th>Size</th>
			<th>Price</th>
			<th>Qty</th>
			<th>Old Price</th>
			<th>Stock</th>
			<th>Status</th>
		</tr>
	
	<?php
	$items = $this->Session->read('Stock.consolidated');
	$trans = $this->Session->read('Stock.trans');
	
	foreach($items['Stock'] as $item ){
		echo '<tr>';
		echo '<td>'.$item['brand'].'</td>';
		echo '<td>'.$item['model'].'</td>';
		echo '<td>'.$item['colour'].'</td>';
		echo '<td>'.$item['size'].'</td>';
		echo '<td>'.$item['price'].'</td>';
		echo '<td>'.$item['stock'].'</td>';
		if(isset($item['oldprice'])){ echo "<td>".$item['oldprice']."</td>";}else{ echo "<td></td>";};
		if(isset($item['oldstock'])){ echo "<td>".$item['oldstock']."</td>";}else{ echo "<td></td>";};
		echo '<td>'.$item['status'].'</td>';
		echo '</tr>';
	}

	?>
	
	
	
	</table>
	
	<strong>Total : Rp. <?php echo $items['Total']?> </strong>
	<?php 
	if($trans == 'sales'){
		echo '<br><strong> Total Capital : Rp.'.$items['Capital'].'</strong>';
		echo '<br><strong> Total Margin : Rp.'.$items['Profit'].'</strong>';
	}
	?>
	
	<div class="submit_button">
		<?php 
		if($trans == 'add'){
			echo $this->Form->create('Stock',array('action'=>'entry'));
		}else{
			echo $this->Form->create('Stock',array('action'=>'out'));
		}
		?>
		<?php echo $this->Form->end('Submit');?>
	</div>
	
	<?php //echo $this->element('sql_dump'); ?>
    
</div>