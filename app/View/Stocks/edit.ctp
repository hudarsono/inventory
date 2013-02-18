<div class="span-24 last">
	<h2>Edit Item</h2>
	
	<?php echo $this->Form->create('Stock')?>
	<fieldset>
		<?php echo $this->Session->flash();?>
		<label for="catId">Brand</label>
		<?php echo $this->BrandList->select($brands, 'cat_id', $this->data['Stock']['cat_id'])?>
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('model', array('div'=>false))?>
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('colour', array('div'=>false))?>
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('size', array('div'=>false))?>
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('stock', array('div'=>false, 'readonly'=>'readonly'))?>
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('booked', array('div'=>false))?>
		<div class="clear" style="height:10px"></div>
		
		<?php echo $this->Form->input('price', array('div'=>false))?>
	</fieldset>
	<?php echo $this->Form->end('Add');?>
</div>