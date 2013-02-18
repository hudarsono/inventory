<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<?php
  $now = time();
  echo $this->Html->css("blueprint/print.css", null, array('media'=>'print'));
  echo $this->Html->css("blueprint/screen.css", null, array('media'=>'screen, projection'));
  echo $this->Html->css("ui-lightness/jquery-ui-1.8.6.custom.css");
	echo $this->Html->css("front.css?id=$now");
	
	echo $this->Html->script("jquery-1.5.1.min");
	echo $this->Html->script("jquery-ui-1.8.11.custom.min");
	echo $this->Html->script("jquery.uitablefilter");
	echo $this->Html->script("common.js?id=$now");
?>
    <!--[if lte IE 7]><?php echo $html->css('blueprint/ie.css') ?><![endif]-->

	

    <title>
        Stock
        <?php echo $title_for_layout; ?>
    </title>

    <?php echo $scripts_for_layout ?>
</head>
<body>
	<div class="container">
		<div class="span-24 last">
			<h1>Stock Management</h1>
		</div>
		<div class="span-24 last">
            <div id="nav-menu">
                <ul>
                    <li><?php echo $this->Html->link('Stocks', array('controller'=>'stocks','action'=>'index'))?></li>
                    <li><?php echo $this->Html->link('Brands', array('controller'=>'categories','action'=>'index'))?></li>
					<li><?php echo $this->Html->link('Sales', array('controller'=>'transactions','action'=>'index'))?></li>
					<li><?php echo $this->Html->link('Users', array('controller'=>'users','action'=>'index'))?></li>
                </ul>
            </div>
        </div>
		<div class="clear" style="height:10px"></div>
		<?php echo $content_for_layout ?>

	</div>
</body>
</html>


