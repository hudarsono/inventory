<?php
App::uses('AppController', 'Controller');

class StocksController extends AppController{
	var $name = "Stocks";
	var $uses = array('Stock','Category', 'User');
	var $helpers = array('Js', 'Html');
	
	function index(){
		$stocks = $this->Stock->find('all',array(
			'conditions'	=> array('Stock.deleted' => 0)
		));
		$this->set('stocks', $stocks);
		
		// get all brand
		$brands = $this->Category->find('all');
		$this->set('brands', $brands);
		
		//calculate total capital
		$total = $this->Stock->find('all', array(
			'fields'		=> array('SUM(Stock.total_price) AS total')
		));
		
		$this->set('total', $total[0][0]['total']);
		
	}
	
	function consolidate(){
		if(!empty($this->data)){
			if($consolidated = $this->Stock->consolidate($this->data, $this->data['trans'])){
				
				if($this->data['trans'] == 'add'){
					// save consolidated statement into session
					$this->Session->write('Stock.consolidated', $consolidated);
					$this->Session->write('Stock.trans', 'add');
					$this->render('consolidate');
				}else{
					// save consolidated statement into session
					$this->Session->write('Stock.consolidated', $consolidated);
					$this->Session->write('Stock.trans', 'sales');
					$this->Session->write('Stock.seller', $this->data['seller']);
					$this->render('consolidate');
				}
				//exit();
			}else{
				$this->Session->setFlash('Oops, please fix errors below.', 'default', array('class'=>'error'));
			}
		}
		
	}
	
	
	function add(){
		// clear session
		$this->Session->delete('Stock');
	}
	
	function entry(){
		$items = $this->Session->read('Stock.consolidated');
		// clear session
		$this->Session->delete('Stock');

		if($this->Stock->entry($items)){
			$this->Session->setFlash('Successful Entry', 'default', array('class'=>'success'));
		}else{
			$this->Session->setFlash('Oops, there was an error. Please check log.', 'default', array('class'=>'error'));
		}
		$this->redirect('index');
	}
	
	function edit($id = null){
		if(!empty($this->data)){
			$this->Stock->id = $id;
			$edited = $this->data;
			$edited['total_price'] = $edited['Stock']['price'] * $edited['Stock']['stock']; 
			if($this->Stock->save($edited)){
				$this->Session->setFlash('Item Updated', 'default', array('class'=>'success'));
				$this->redirect('index');
			}else{
				$this->Session->setFlash('Oops, please fix errors below.', 'default', array('class'=>'error'));
			}
		}
		$this->Stock->contain();
		$this->data = $this->Stock->findById($id);
		
		// get all brand
		$brands = $this->Category->find('all');
		$this->set('brands', $brands);
	}
	
	function delete($id=null){
		if($this->Stock->delete($id)){
			$this->Session->setFlash('Item deleted', 'default', array('class'=>'success'));
			$this->redirect('index');
		}else{
			$this->Session->setFlash('Oops, there was an error.', 'default', array('class'=>'error'));
		}
	}
	
	function deleteSelected(){
		$cids = explode(',',$this->data['cid']);
		
		foreach($cids as $c){
			// delete one by one
			$this->Stock->cdelete($c);
		}
		
		$this->Session->setFlash(__('Items deleted', true), 'default', array('class'=>'success'));
		$this->redirect('index');
	}
	
	
	function sales(){
		// clear session
		$this->Session->delete('Stock');
		
		// get all users
		$users = $this->User->find('all');
		$this->set('users', $users);
	}
	
	
	function out(){
		$items = $this->Session->read('Stock.consolidated');
		$seller = $this->Session->read('Stock.seller');
		// clear session
		$this->Session->delete('Stock');

		if($this->Stock->sales($items, $seller)){
			$this->Session->setFlash('Successful Sales', 'default', array('class'=>'success'));
		}else{
			$this->Session->setFlash('Oops, there was an error. Please check log.', 'default', array('class'=>'error'));
		}
		$this->redirect('index');
	}
	
	function booking($id=null){
		
		if(!empty($this->data)){
			if($this->Stock->book($this->data)){
				$this->Session->setFlash('Booking Processed', 'default', array('class'=>'success'));
				$this->redirect('index');
			}else{
				$this->Session->setFlash('Oops, please fix errors below.', 'default', array('class'=>'error'));
			}
		}
		if($id){
			$this->Stock->contain();
			$this->data = $this->Stock->findById($id);
		}
		// get all brand
		$brands = $this->Category->find('all');
		$this->set('brands', $brands);
	}
	
	function cancelbook($id=null){
		if($id){
			if($this->Stock->cancelbook($id)){
				$this->Session->setFlash('Booking Canceled', 'default', array('class'=>'success'));
				$this->redirect('index');
			}else{
				$this->Session->setFlash('Oops, please fix errors below.', 'default', array('class'=>'error'));
			}
		}
		$this->redirect('index');
	}
	
	function search(){
		if(!empty($this->data)){
			$conditions = array();
			if(!empty($this->data['Stock']['cat_id'])){
				$conditions['Stock.cat_id'] = $this->data['Stock']['cat_id'];
			}
			if(!empty($this->data['Stock']['model'])){
				$conditions['Stock.model'] = $this->data['Stock']['model'];
			}
			if(!empty($this->data['Stock']['colour'])){
				$conditions['Stock.colour'] = $this->data['Stock']['colour'];
			}
			
			$stocks = $this->Stock->find('all', array(
				'conditions' 		=> $conditions,
				'order'				=> array('Stock.stock')
			));
			
			$this->set('stocks', $stocks);
			
		}
		$this->redirect('index');
	}
	
	function ajax_get_brand(){
		Configure::write ( 'debug', 0 );
	 	$this->autoRender = false;
		
		$term = $this->params['url']['term'];
		
		$this->Category->contain();
		$brands = $this->Category->find('all', array(
			'conditions'	=> array('Category.brand LIKE' => $term.'%'),
			'fields'		=> array('Category.brand'),
			'group'			=> array('Category.brand'),
			'limit'			=> 10
		));
		
		
		if(!empty($brands)){
			foreach($brands as $brand){
				$results[] = $brand['Category']['brand'];
			}
		}elseif(strlen($term) == 1){
			// if empty, get all records
			$brands = $this->Category->find('all', array(
				'fields'		=> array('Category.brand'),
				'group'			=> array('Category.brand'),
				'limit'			=> 10
			));
			
			foreach($brands as $brand){
				$results[] = $brand['Category']['brand'];
			}
		}
				
		echo json_encode($results);
		
	}
	
	function ajax_get_model(){
		Configure::write ( 'debug', 0 );
	 	$this->autoRender = false;
		
		$term = $this->params['url']['term'];
		$brand = $this->params['url']['brand'];
		$cat_id = $this->Category->getIdByBrand($brand);
		
		
		$this->Stock->contain();
		$models = $this->Stock->find('all', array(
			'conditions'	=> array(
										'Stock.model LIKE' => $term.'%',
										'Stock.cat_id'	=> $cat_id),
			'fields'		=> array('Stock.model'),
			'group'			=> array('Stock.model'),
			'limit'			=> 10
		));
		
		if(!empty($models)){
			foreach($models as $model){
				$results[] = $model['Stock']['model'];
			}
		}elseif(strlen($term) == 1){
			// if empty, get all records
			$models = $this->Stock->find('all', array(
				'conditions'	=> array('Stock.cat_id'	=> $cat_id),
				'fields'		=> array('Stock.model'),
				'group'			=> array('Stock.model'),
				'limit'			=> 10
			));
			
			foreach($models as $model){
				$results[] = $model['Stock']['model'];
			}
		}
				
		echo json_encode($results);
		
	}
	
	function ajax_get_colour(){
		Configure::write ( 'debug', 0 );
	 	$this->autoRender = false;
		
		$term = $this->params['url']['term'];
		$brand = $this->params['url']['brand'];
		$cat_id = $this->Category->getIdByBrand($brand);
		$model = $this->params['url']['model'];
		
		$this->Stock->contain();
		$colours = $this->Stock->find('all', array(
			'conditions'	=> array('Stock.colour LIKE' => $term.'%',
										'Stock.cat_id'	=> $cat_id,
										'Stock.model LIKE' => $model.'%'),
			'fields'		=> array('Stock.colour'),
			'group'			=> array('Stock.colour'),
			'limit'			=> 10
		));
		
		if(!empty($colours)){
			foreach($colours as $colour){
				$results[] = $colour['Stock']['colour'];
			}
		}elseif(strlen($term) == 1){
			$colours = $this->Stock->find('all', array(
				'conditions'	=> array(	'Stock.cat_id'	=> $cat_id,
											'Stock.model LIKE' => $model.'%'),
				'fields'		=> array('Stock.colour'),
				'group'			=> array('Stock.colour'),
				'limit'			=> 10
			));
			
			foreach($colours as $colour){
				$results[] = $colour['Stock']['colour'];
			}
		}
		
		echo json_encode($results);
	}
	
	function ajax_get_size(){
		Configure::write ( 'debug', 0 );
	 	$this->autoRender = false;
		
		$term = $this->params['url']['term'];
		$brand = $this->params['url']['brand'];
		$cat_id = $this->Category->getIdByBrand($brand);
		$model = $this->params['url']['model'];
		$colour = $this->params['url']['colour'];
		
		$this->Stock->contain();
		$sizes = $this->Stock->find('all', array(
			'conditions'	=> array('Stock.size LIKE' => $term.'%',
										'Stock.cat_id'	=> $cat_id,
										'Stock.model LIKE' => $model.'%',
										'Stock.colour LIKE' => $colour.'%'),
			'fields'		=> array('Stock.size'),
			'group'			=> array('Stock.size'),
			'limit'			=> 10
		));
		
		if(!empty($sizes)){
			foreach($sizes as $size){
				$results[] = $size['Stock']['size'];
			}
		}elseif(strlen($term) == 1){
			$sizes = $this->Stock->find('all', array(
				'conditions'	=> array(	'Stock.cat_id'	=> $cat_id,
											'Stock.model LIKE' => $model.'%',
											'Stock.colour LIKE' => $colour.'%'),
				'fields'		=> array('Stock.size'),
				'group'			=> array('Stock.size'),
				'limit'			=> 10
			));
			
			foreach($sizes as $size){
				$results[] = $size['Stock']['size'];
			}
		}
		
		echo json_encode($results);
	}
	
	function ajax_get_model_of_brand($cat_id=null){
		Configure::write ( 'debug', 0 );
	 	$this->autoRender = false;
	
		$this->Stock->contain();
		$models = $this->Stock->find('all', array(
			'conditions'	=> array('Stock.cat_id' => $cat_id),
			'fields'		=> array('Stock.model'),
			'group'			=> array('Stock.model')
		));
		
		if(!empty($models)){
			foreach($models as $model){
				$results[] = $model['Stock']['model'];
			}
		}
		
		echo json_encode($results);
	}
	
	function ajax_get_colour_of_brandmodel(){
		Configure::write ( 'debug', 0 );
	 	$this->autoRender = false;
	
		$this->Stock->contain();
		$colours = $this->Stock->find('all', array(
			'conditions'	=> array('Stock.cat_id' => $this->data['cat_id'],
										'Stock.model' => $this->data['model']),
			'fields'		=> array('Stock.colour'),
			'group'			=> array('Stock.colour')
		));
		
		if(!empty($colours)){
			foreach($colours as $colour){
				$results[] = $colour['Stock']['colour'];
			}
		}
		
		echo json_encode($results);
	}
	
	function ajax_get_size_of_brandmodelcolour(){
		Configure::write ( 'debug', 0 );
	 	$this->autoRender = false;
	
		$this->Stock->contain();
		$sizes = $this->Stock->find('all', array(
			'conditions'	=> array('Stock.cat_id' => $this->data['cat_id'],
										'Stock.model' => $this->data['model'],
										'Stock.colour' => $this->data['colour']),
			'fields'		=> array('Stock.size'),
			'group'			=> array('Stock.size')
		));
				
		
		if(!empty($sizes)){
			foreach($sizes as $size){
				if(!empty($size['Stock']['size'])){
					$results[] = $size['Stock']['size'];
				}
				
			}
			
		}
		
		echo json_encode($results);
		
		
	}
	
	function ajax_get_price(){
		Configure::write ( 'debug', 0 );
	 	$this->autoRender = false;
	
		$this->Stock->contain();
		$cat_id = $this->Category->getIdByBrand($this->data['cat_id']);
		$price = $this->Stock->find('first', array(
			'conditions'	=> array('Stock.cat_id' => $cat_id,
										'Stock.model' => $this->data['model'],
										'Stock.colour' => $this->data['colour'],
										'Stock.size' => $this->data['size']),
			'fields'		=> array('Stock.price'),
		));
				
		
		if(!empty($price)){
			$result = array('status' => 1,'data'=>$price['Stock']['price']);
			
		}else{
			$result = array('status' => 0);
		}
		
		echo json_encode($result);
	}
	
	function beforeFilter() {
		parent::beforeFilter();
		#$this->Security->validatePost = false;
        $this->helpers[] = 'BrandList';
	}
	
	
}