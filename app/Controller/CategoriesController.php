<?php
App::uses('AppController', 'Controller');

class CategoriesController extends AppController{
	var $name = "Categories";
	var $uses = array('Category');
	
	function index(){
		$cats = $this->Category->find('all');
		$this->set('cats', $cats);
	}
	
	function add(){
		if(!empty($this->data)){
			if($this->Category->save($this->data)){
				$this->Session->setFlash('New Brand Added', 'default', array('class'=>'success'));
				$this->redirect('index');
			}else{
				$this->Session->setFlash('Oops, please fix errors below.', 'default', array('class'=>'error'));
			}
		}
	}
	
	function edit($id=null){
		if(!empty($this->data)){
			$this->Category->id = $id;
			if($this->Category->save($this->data)){
				$this->Session->setFlash('Brand Updated', 'default', array('class'=>'success'));
				$this->redirect('index');
			}else{
				$this->Session->setFlash('Oops, please fix errors below.', 'default', array('class'=>'error'));
			}
		}
		
		
		if(!empty($id)){
			$this->data = $this->Category->findById($id);
		}else{
			$this->redirect('index');
		}
	}
	
	function delete($id=null){
		if(!empty($id)){
			if($this->Category->delete($id)){
				$this->Session->setFlash('Brand Deleted', 'default', array('class'=>'success'));
				$this->redirect('index');
			}else{
				$this->Session->setFlash('Oops, there was an error. Please try again.', 'default', array('class'=>'error'));
			}
		}else{
			$this->redirect('index');
		}
	}
	
}