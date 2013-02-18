<div class="span-24 last">
	<h2>Sales</h2>
	
	<?php echo $this->Session->flash();?>
	
	<?php echo $this->Form->create('Transaction', array('action' => 'index'))?>
	<table id="filder">
		<td>Seller</td>
		<td><select name="data[Transaction][seller]" id="TransactionSeller">
			<option value="">All</option>
			<?php
			foreach($users as $user){
				echo '<option value="'.$user['User']['id'].'">'.$user['User']['name'].'</option>';
			}?>
			
		</select></td>
		<!--<td>Brand</td>
		<td><?php echo $this->BrandList->select($brands, 'cat_id')?></td>-->
		<td>Date</td>
		<td><input type="text" id="mindate" name="data[Transaction][min_date]"/> to <input type="text" id="maxdate" name="data[Transaction][max_date]"/></td>
	</table>
	<?php echo $this->Form->end('Filter');?>
	
	<div style="float:right;font-size:16px">
	<strong>Margin : Rp. <?php echo number_format($margin,0,'.','.') ?> | Total : Rp. <?php echo number_format($total,0,'.','.')?></strong>
	</div>
	<table id="SalesList">
		<thead>
			<tr>
				<th>Action</th>
				<th>Date</th>
				<th>Name</th>
				<th>Item</th>
				<th>Qty</th>
				<th>Price</th>
				<th>Total Sales</th>
			</tr>
			<tr id="filter">
				<th></th>
				<th><input type="text" id="filter_date" /></th>
				<th><input type="text" id="filter_name" /></th>
				<th><input type="text" id="filter_item" /></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php if(!empty($trans)){
			foreach($trans as $tran){
				echo '<tr>';
				echo '<td>'.$this->Html->link('Edit', array('action'=>'edit', $tran['Transaction']['id'])).'</td>';
				echo '<td>'.$tran['Transaction']['date'].'</td>';
				echo '<td>'.$tran['User']['name'].'</td>';
				echo '<td>'.$tran['Category']['brand'].' - '.$tran['Stock']['model'].' - '.$tran['Stock']['colour'].' - '.$tran['Stock']['size'];
				if($tran['Transaction']['type'] == 'book'){
					echo ' {'.$tran['Transaction']['name'].'}['.$this->Html->link('Cancel', array('controller'=>'stocks', 'action'=>'cancelbook', $tran['Transaction']['id'])).']';
				}
				echo '</td>';
				echo '<td>'.$tran['Transaction']['qty'].'</td>';
				echo '<td class="right">'.number_format($tran['Transaction']['price'],0,'.','.').'</td>';
				echo '<td class="right">'.number_format($tran['Transaction']['total_price'],0,'.','.').'</td>';
				echo '</tr>';
			}
		}?>
		</tbody>
	</table>
	<?php //echo $this->element('sql_dump'); ?>
</div>

