<div class="span-24 last">
	<h2>Item Transaction</h2>
	
	<?php echo $this->Session->flash();?>
	
	<table id="SalesList">
		<thead>
			<tr>
				<th>Date</th>
				<th>Type</th>
				<th>Qty</th>
				<th>Price</th>
				<th>Total Price</th>
			</tr><!--
			<tr id="filter">
				<th><input type="text" id="filter_date" /></th>
				<th><input type="text" id="filter_item" /></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>-->
		</thead>
		<tbody>
		<?php if(!empty($trans)){
			foreach($trans as $tran){
				echo '<tr>';
				echo '<td>'.$tran['Transaction']['created'].'</td>';
				echo '<td>'.$tran['Transaction']['type'].'</td>';
				echo '<td>'.$tran['Transaction']['qty'].'</td>';
				echo '<td class="right">'.number_format($tran['Transaction']['price'],0,'.','.').'</td>';
				echo '<td class="right">'.number_format($tran['Transaction']['total_price'],0,'.','.').'</td>';
				echo '</tr>';
			}
		}?>
		</tbody>
	</table>
</div>