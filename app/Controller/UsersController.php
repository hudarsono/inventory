<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController{
	var $name = "Users";
	var $uses = array('User');
	
	function index(){
		$users = $this->User->find('all');
		$this->set('users', $users);
	}
	
	function add(){
		if(!empty($this->data)){
			if($this->User->save($this->data)){
				$this->Session->setFlash('New User Added', 'default', array('class'=>'success'));
				$this->redirect('index');
			}else{
				$this->Session->setFlash('Oops, please fix errors below.', 'default', array('class'=>'error'));
			}
		}
	}
	
	function edit($id=null){
		if(!empty($this->data)){
			$this->User->id = $id;
			if($this->User->save($this->data)){
				$this->Session->setFlash('User Updated', 'default', array('class'=>'success'));
				$this->redirect('index');
			}else{
				$this->Session->setFlash('Oops, please fix errors below.', 'default', array('class'=>'error'));
			}
		}
		
		
		if(!empty($id)){
			$this->data = $this->User->findById($id);
		}else{
			$this->redirect('index');
		}
	}
	
	function delete($id=null){
		if(!empty($id)){
			if($this->User->delete($id)){
				$this->Session->setFlash('User Deleted', 'default', array('class'=>'success'));
				$this->redirect('index');
			}else{
				$this->Session->setFlash('Oops, there was an error. Please try again.', 'default', array('class'=>'error'));
			}
		}else{
			$this->redirect('index');
		}
	}
	
}