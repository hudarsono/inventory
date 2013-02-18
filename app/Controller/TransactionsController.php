<?php
App::uses('AppController', 'Controller');

class TransactionsController extends AppController{
	var $name = "Transactions";
	var $uses = array('Transaction','Category','User');
	var $helpers = array('BrandList');
	
	var $paginate = array(
            'limit' => 100,
            'order' => array('Transaction.date' => 'desc')
        );

	
	function index(){
		if(!empty($this->data)){
			$conditions = array('Transaction.type' => 'sales');
			if(!empty($this->data['Transaction']['seller'])){
				$conditions['Transaction.user_id'] = $this->data['Transaction']['seller'];
			}
			
			if(!empty($this->data['Transaction']['min_date'])){
				$conditions['Transaction.date >='] = $this->data['Transaction']['min_date'];
			}
			
			if(!empty($this->data['Transaction']['max_date'])){
				$conditions['Transaction.date <='] = $this->data['Transaction']['max_date'];
			}
						
			
			$this->Transaction->contain('User');
			$trans = $this->paginate('Transaction', $conditions);
		}else{
			$this->Transaction->contain('User');
			$trans = $this->paginate('Transaction', array('Transaction.type' => 'sales'));
		}

		
		//calculate total
		$total = 0;
		$margin= 0;
		foreach($trans as $tran){
			$total = $total + $tran['Transaction']['total_price'];
			$margin = $margin + $tran['Transaction']['total_margin'];
		}
		
		$this->set('total', $total);
		$this->set('margin', $margin);
		
		$i=0;
		foreach($trans as $tran){
			// get product name
			$this->Category->contain();
			$item = $this->Category->findById($tran['Stock']['cat_id']);
			$trans[$i]['Category'] = $item['Category'];
			$i++;
		}
		
		$this->set('total', $total);
				
		$this->set('trans', $trans);
		
		// get all users
		$users = $this->User->find('all');
		$this->set('users', $users);
		
		// get all brand
		$brands = $this->Category->find('all');
		$this->set('brands', $brands);
	}
	
	function edit($id = null){
		if(!empty($this->data)){
			$this->Transaction->id = $id;
			$data = $this->data;
			$data['Transaction']['created']['hour'] = date('g');
			$data['Transaction']['created']['min'] = date('i');
			$data['Transaction']['created']['meridian'] = date('a');
			if($this->Transaction->save($data)){
				$this->Session->setFlash('Sales Updated', 'default', array('class'=>'success'));
				$this->redirect('index');
			}else{
				$this->Session->setFlash('Oops, please fix errors below.', 'default', array('class'=>'error'));
			}
		}
		// get all brand
		$this->Transaction->contain('User');
		$this->data = $this->Transaction->findById($id);
		file_put_contents('/users/hudarsono/data.log',print_r($this->data,true)."\n",FILE_APPEND);
	}
	
	function itemtrans($id = null){
		if($id){
			$trans = $this->paginate('Transaction', array('Transaction.stock_id' => $id));
			$this->set('trans', $trans);
		}
	}
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Security->validatePost = false;
        $this->helpers[] = 'BrandList';
	}
}