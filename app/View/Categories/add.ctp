<div class="span-24 last">
	<h2>Add New Brand</h2>
	
	<?php echo $this->Form->create('Category');?>
	<fieldset>
			<?php echo $this->Session->flash();?>
		
			<?php echo $this->Form->input('brand', array('div'=>false,'label'=>'Brand', 'class'=>'text'))?>
		
	</fieldset>
	<?php echo $this->Form->end('Save')?>
</div>