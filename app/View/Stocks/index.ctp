<!--
<div class="span-24 last">
	<h2>Search</h2>
	<?php echo $this->Form->create('Stock', array('action'=>'search'))?>
	<table id="search">
		<tbody>
			<tr>
				
				<td>Brand</td>
				<td><?php echo $this->BrandList->select($brands, 'cat_id')?></td>
				<td>Model</td>
				<td><?php echo $this->Form->select('model', array('div'=>false, 'label'=>false))?></td>
				<td>Color</td>
				<td><?php echo $this->Form->select('colour', array('div'=>false, 'label'=>false))?></td>
				<td><?php echo $this->Form->end('Search')?></td>
			</tr>
		</tbody>
	</table>
	
</div>
-->
<div class="span-24 last">
	<h2>Stock List</h2>
	
	<?php echo $this->Session->flash();?>
	<ul id="top-menu">
		<li><?php echo $this->Html->link('Add Item', array('action'=>'add'));?></li>
		<li><?php echo $this->Html->link('Sales', array('action'=>'sales'));?></li>
		<li><?php echo $this->Html->link('Booking', array('action'=>'booking'));?></li>
	</ul><br/><br/>
	
	<form method="post" action="/stocks/deleteSelected" id="delMessageForm" style="float:left">
		<input type="hidden" name="data[cid]" id="cid"/>
		<input type="button" class="btnDelete" value="Delete Selected" onclick="delItems()"/>
	</form>
	<div style="float:right;font-size:16px">

	<strong>Total : Rp. <?php echo number_format($total,0,'.','.')?></strong>
	</div>
	
	<table id="StockList">
		<thead>
			<tr>
				<th>Select</th>
				<th>Brand</th>
				<th>Model</th>
				<th>Colour</th>
				<th>Stock</th>
				<th>Booked</th>
				<th>Size</th>
				<th>Price</th>
				<th>Total Price</th>
				<th class="small">Action</th>
			</tr>
			<tr id="filter">
				<form id="filter-form">
				<th></th>
				<th><input type="text" id="filter_brand" /></th>
				<th><input type="text" id="filter_model" /></th>
				<th><input type="text" id="filter_colour" /></th>
				<th><input type="text" id="filter_stock" style="width:40px"/></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				</form>
			</tr>
		</thead>

		<tbody>
		<?php
		if(!empty($stocks)){
			foreach($stocks as $stock){
				echo '<tr>';
				echo '<td><input type="checkbox" name="cid[]" value="'.$stock['Stock']['id'].'";"/></td>';
				echo '<td>'.$stock['Category']['brand'].'</td>';
				echo '<td>'.$stock['Stock']['model'].'</td>';
				echo '<td>'.$stock['Stock']['colour'].'</td>';
				echo '<td>'.$stock['Stock']['stock'].'</td>';
				echo '<td>'.$stock['Stock']['booked'].'</td>';
				echo '<td>'.$stock['Stock']['size'].'</td>';
				echo '<td class="right">'.number_format($stock['Stock']['price'],0,'.','.').'</td>';
				echo '<td class="right">'.number_format($stock['Stock']['total_price'],0,'.','.').'</td>';
				echo '<td>'.$this->Html->link('Trans', array('controller' => 'transactions', 'action'=>'itemtrans', $stock['Stock']['id'])).' | '.
							//$this->Html->link('Book', array('action'=>'booking', $stock['Stock']['id'])).' | '.
							$this->Html->link('Edit', array('action'=>'edit', $stock['Stock']['id'])).' | '.
							$this->Html->link('Del', array('action'=>'delete', $stock['Stock']['id'])).'</td>';
				echo '</tr>';
			
			}
		}else{
            echo '<tr><td colspan="7">No stock was found.</td></tr>';
        }
		?>
		</tbody>
	</table>
</div>