<div class="span-24 last">
    <h2>Edit User</h2>

	<?php echo $this->Form->create('User')?>
	<fieldset>
		<?php echo $this->Session->flash();?>
		
		<?php echo $this->Form->input('name', array('div'=>false,'label'=>'Name', 'class'=>'text'))?>
	</fieldset>
	<?php echo $this->Form->end('Update')?>
</div>