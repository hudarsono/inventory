<div class="span-24 last">
	<h2>Sales</h2>
	
	<?php echo $this->Form->create('Stock', array('action' => 'consolidate'))?>
	<fieldset>
		<?php echo $this->Session->flash();?>
		<input type="hidden" name="data[trans]" value="sales"/>
		<div class="floatform">
		<label for="StockUserId">Seller</label>
		<select name="data[seller]">
			<?php
			foreach($users as $user){
				echo '<option value="'.$user['User']['id'].'">'.$user['User']['name'].'</option>';
			}?>
			
		</select>
		</div>
		<br><br><br>
		
		<?php echo $this->Javascript->link("prototype", false); ?>
		
		<!-- echo 5 forms by default -->
		<div id="items">
		<?php
		for($i=0;$i<5;$i++){
		?>
			<?php echo $this->Ajax->div("AddContainer".$i); ?>
			<div class="floatform">
			<?php if($i==0){echo '<label for="StockBrand">Brand</label>';}else{echo '';}?>
			<?php echo $this->Form->input($i.'.brand', array('div'=>false, 
							'label' => false,'onkeypress'=>'return event.keyCode!=13'))?>
			</div>
			<div class="floatform">
			<?php if($i==0){echo '<label for="StockModel">Model</label>';}else{echo '';}?>
			<?php echo $this->Form->input($i.'.model', array('div'=>false, 
							'label' => false,'onkeypress'=>'return event.keyCode!=13'))?>
			</div>
			<div class="floatform">
			<?php if($i==0){echo '<label for="StockColour">Colour</label>';}else{echo '';}?>
			<?php echo $this->Form->input($i.'.colour', array('div'=>false, 'label' => false,'onkeypress'=>'return event.keyCode!=13'))?>
			</div>
			<div class="floatform">
			<?php if($i==0){echo '<label for="StockSize">Size</label>';}else{echo '';}?>
			<?php echo $this->Form->input($i.'.size', array('div'=>false, 'label' => false,'onkeypress'=>'return event.keyCode!=13'))?>
			</div>
			<div class="floatform">
			<?php if($i==0){echo '<label for="StockStock">Qty</label>';}else{echo '';}?>
			<?php echo $this->Form->input($i.'.stock', array('div'=>false, 'label' => false,'onkeypress'=>'return event.keyCode!=13'))?>
			</div>
			<div class="floatform">
			<?php if($i==0){echo '<label for="StockPrice">Price</label>';}else{echo '';}?>
			<?php echo $this->Form->input($i.'.price', array('div'=>false, 'label' => false,'onkeypress'=>'return event.keyCode!=13'))?>
			</div>
			<div class="floatform">
			<?php if($i==0){echo '<label for="StockDate">Date</label>';}else{echo '';}?>
			<?php echo $this->Form->input($i.'.date', array('div'=>false, 'label' => false,'onkeypress'=>'return event.keyCode!=13', 'onfocus'=>'$(this).val("'.date("Y-m-d").'")'))?>
			</div>
			<?php echo $this->Ajax->divEnd("AddContainer".$i);?> 
			<div class="clear"></div>
		<?php }?>
		</div>
		
		<a href="javascript:void(0)" onclick="addItem()">Add Item</a>	

	</fieldset>
	<?php echo $this->Form->end('Submit');?>
</div>