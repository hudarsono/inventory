<div class="span-24 last">
	<h2>Booking</h2>
	
	<?php echo $this->Form->create('Stock')?>
	<fieldset>
		<?php echo $this->Session->flash();?>
		<label for="catId">Brand</label>
		<?php echo $this->BrandList->select($brands, 'cat_id',$this->data['Stock']['cat_id'])?>
		<div class="clear" style="height:10px"></div>
		
		<?php
		if(isset($this->data['Stock']['cat_id'])){?>
			<?php echo $this->Form->input('model', array('div'=>false))?>
			<div class="clear" style="height:10px"></div>
			<?php echo $this->Form->input('colour', array('div'=>false))?>
			<div class="clear" style="height:10px"></div>
			<?php echo $this->Form->input('size', array('div'=>false))?>
			<div class="clear" style="height:10px"></div>
		<?php }else{?>
			<label for="StockModel">Model</label>
			<?php echo $this->Form->select('model', array('div'=>false))?>
			<div class="clear" style="height:10px"></div>
			<label for="StockColour">Colour</label>
			<?php echo $this->Form->select('colour', array('div'=>false))?>
			<div class="clear" style="height:10px"></div>
			<label for="StockSize">Size</label>
			<?php echo $this->Form->select('size', array('div'=>false))?>
			
		<?php }
		?>
		
		
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('stock', array('div'=>false, 'label' => 'Qty'))?>
		
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('price', array('div'=>false))?>
		
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('name', array('div'=>false, 'label' => 'Name'))?>
	</fieldset>
	<?php echo $this->Form->end('Add');?>
</div>