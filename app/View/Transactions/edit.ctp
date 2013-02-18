<div class="span-24 last">
	<h2>Edit Sales</h2>
	
	<?php echo $this->Form->create('Transaction')?>
	<fieldset>
		<?php echo $this->Session->flash();?>
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('date', array('div'=>false, 'type'=>'date','class'=>'date'))?>
		<div class="clear" style="height:10px"></div>
		<label for="TransactionName">Seller</label>
		<input id="TransactionName" type="text" name="data[User][name]" value="<?php echo $this->data['User']['name'] ?>">
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('qty', array('div'=>false))?>
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('price', array('div'=>false, 'readonly'=>'readonly'))?>
		<div class="clear" style="height:10px"></div>
		<?php echo $this->Form->input('total_price', array('div'=>false))?>
		<div class="clear" style="height:10px"></div>
	</fieldset>
	<?php echo $this->Form->end('Save');?>
</div>