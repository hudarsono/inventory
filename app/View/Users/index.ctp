<div class="span-24 last">
	<h2>User List</h2>
	
	<?php echo $this->Session->flash();?>
	<?php echo $this->Html->link('Add User', array('action'=>'add'));?>
	<table>
		<thead>
			<tr>
				<th>Action</th>
				<th>Name</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($users)){
			foreach($users as $user){
				echo '<tr>';
				echo '<td width="100">'.$this->Html->link('Edit', array('action'=>'edit', $user['User']['id'])).' | '.
							$this->Html->link('Delete', array('action'=>'delete', $user['User']['id'])).'</td>';
				echo '<td>'.$user['User']['name'].'</td>';
				echo '</tr>';
			}
		}
		?>
		</tbody>
	</table>
</div>