<?php
App::uses('AppHelper', 'Helper');

class BrandListHelper extends AppHelper{
	var $helpers = array('Form');
	
	function select($brands, $fieldname, $attrs = []){
		foreach($brands as $brand){
			$data[$brand['Category']['id']] = $brand['Category']['brand'];
		}
		
		return $this->Form->select($fieldname, $data, $attrs);
	}
	
}
?>