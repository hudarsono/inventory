<div class="span-24 last">
	<h2>Brand List</h2>
	
	<?php echo $this->Session->flash();?>
	<?php echo $this->Html->link('Add Brand', array('action'=>'add'));?>
	<table>
		<thead>
			<tr>
				<th>Action</th>
				<th>Brand</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($cats)){
			foreach($cats as $cat){
				echo '<tr>';
				echo '<td width="100">'.$this->Html->link('Edit', array('action'=>'edit', $cat['Category']['id'])).' | '.
							$this->Html->link('Delete', array('action'=>'delete', $cat['Category']['id'])).'</td>';
				echo '<td>'.$cat['Category']['brand'].'</td>';
				echo '</tr>';
			}
		}
		?>
		</tbody>
	</table>
</div>