<?php
App::import('Security');
App::import('Sanitize');

class Category extends AppModel{
	var $name = "Category";
	var $actsAs = array('Containable');
	
	var $validate = array(
        'brand' => array('rule' =>'notEmpty')
    );
	
	var $hasMany = array(
        'Stock' =>
            array(
                'className'              => 'Stock',
                'foreignKey'             => 'cat_id',
            ),
    );


	function getBrandById($id){
		$cat = $this->findById($id);
		if($cat){
			return $cat['Category']['brand'];
		}else{
			return false;
		}
	}
	
	function getIdByBrand($brand){
		$cat = $this->findByBrand($brand);
		if($cat){
			return $cat['Category']['id'];
		}else{
			return false;
		}
	}
	
	function addBrand($brand){
		if($this->save(array('Category' => array('brand' => $brand)))){
			return $this->id;
		}else{
			return false;
		}
	}
}