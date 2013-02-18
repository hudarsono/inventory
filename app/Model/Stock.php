<?php
App::import('Security');
App::import('Sanitize');

class Stock extends AppModel{
	var $name = "Stock";
	var $actsAs = array('Containable');
	
	var $validate = array(
		'cat_id' => array('rule' =>'notEmpty'),
        'brand' => array('rule' =>'notEmpty'),
		'model' => array('rule' =>'notEmpty'),
		'colour' => array('rule' =>'notEmpty'),
		'price' => array('rule' =>'numeric',
						 'required' => true),
		'stock' => array('rule' =>'numeric',
						 'required' => true),
    );
	
	
	var $belongsTo = array(
        'Category' =>
            array(
                'className'              => 'Category',
                'foreignKey'             => 'cat_id',
                'unique'                 => true,
            ),
    );

	var $hasMany = array(
	        'Transaction' =>
	            array(
	                'className'              => 'Transaction',
	                'foreignKey'             => 'stock_id',
	            ),
	    );
	
	function consolidate($data, $type){
		App::import('model', 'Category');
		$category = new Category();
		
		$consolidated = '';
		
		$i = 0;
		foreach($data['Stock'] as $item){
			// check only non empty items
			if(!empty($item['brand']) && !empty($item['model']) && !empty($item['colour'])){
				// get cat id
				$cat_id = $category->getIdByBrand($item['brand']);
				if(empty($cat_id)){$cat_id = 0;}
				
				// check stock in database
				$conditions = array();
				$conditions =  array('Stock.cat_id' => $cat_id,
											'Stock.model' => $item['model'],
											'Stock.colour' => $item['colour']);
											
				if(!empty($item['size'])){
					$conditions['Stock.size'] = $item['size'];
				}
				
				$stored = '';
				$this->contain('Category');
				
				$stored = $this->find('first', array('conditions' => $conditions));
				
				if(empty($item['date'])) $item['date'] = date('Y-m-d');

				
				if(!empty($stored)){
					$consolidated['Stock'][$i] = array(	'status' => '',
														'cat_id' => $stored['Stock']['cat_id'],
														'brand'	=> $stored['Category']['brand'],
														'model'	=> $stored['Stock']['model'],
														'colour' => $stored['Stock']['colour'],
														'size'	=> $stored['Stock']['size'],
														'oldprice' => $stored['Stock']['price'],
														'oldstock' => $stored['Stock']['stock'],
														'price' => $item['price'],
														'stock' => $item['stock'],
														'date' => $item['date']);
				}else{
					$consolidated['Stock'][$i] = array(	'status' => 'new',
														'cat_id' => $cat_id,
														'brand' => $item['brand'],
														'model' => $item['model'],
														'colour'=> $item['colour'],
														'size' => $item['size'],
														'price' => $item['price'],
														'stock' => $item['stock'],
														'date' => $item['date']);
				}
				$i++;
			}
						

		}
		
		if($type == 'add'){
			// Sum up total price
			$total = 0;
			foreach($consolidated['Stock'] as $item){
				$total = $total + ($item['price'] * $item['stock']);
			}
			
			$consolidated['Total'] = $total;
		}else{
			// if type is sales, calculate amount
			$i = 0; 
			$total = 0;
			$total_capital = 0;
			$total_profit = 0;
			foreach($consolidated['Stock'] as $item){
				// check sales entry is correct
				if(isset($item['oldprice'])){
					if($item['oldstock'] >= $item['stock']){
						$total = $total + ($item['price'] * $item['stock']);
						$total_capital = $total_capital + ($item['oldprice'] * $item['stock']);
						$total_profit = $total_profit + (($item['price'] - $item['oldprice']) * $item['stock']);
					}else{
						// stock not enough
						$consolidated['Stock'][$i]['status'] = 'Not enough!';
					}
				}else{
					// stock not exist
					$consolidated['Stock'][$i]['status'] = 'Not exist!';
				}
			}
			
			$consolidated['Total'] = $total;
			$consolidated['Capital'] = $total_capital;
			$consolidated['Profit'] = $total_profit;
		}
		return $consolidated;
	}
	


	function entry($items){
		App::import('model', 'Category');
		$category = new Category();
				
		foreach($items['Stock'] as $item){
			// if item is new
			if($item['status'] == 'new'){
				// check if brand exist
				$cat_id =  $category->getIdByBrand($item['brand']);
				$item['cat_id'] = $cat_id;
				if(empty($cat_id)){
					// create that brand
					$item['cat_id'] = $category->addBrand($item['brand']);
				}
				
				// calculate total price
				$item['total_price'] = $item['price'] * $item['stock'];
				$this->create();
				if(!$this->save($item)){
					$error = true;
					$this->log('Failed saving new item :'.print_r($item,true), 'errors');
				}else{
					// log transaction
					$id = $this->id;
					$trans['Transaction'] = array(
						'stock_id'		=> $id,
						'price'			=> $item['price'],
						'total_price' 	=> $item['price']*$item['stock'],
						'qty'			=> $item['stock'],
						'type'			=> 'add'
					);
								
					$this->Transaction->save($trans);
				}
				
			}else{
				$current = $this->find('first', array(
					'conditions'	=> array('Stock.cat_id' => $item['cat_id'],
												'Stock.model' => $item['model'],
												'Stock.colour' => $item['colour'])
				));
				
				// update new price
				$current['Stock']['total_price'] = 	($current['Stock']['stock']*$current['Stock']['price']) + 
														($item['price'] * $item['stock']);
				$current['Stock']['stock'] = $current['Stock']['stock'] + $item['stock'];
				$current['Stock']['price'] = $current['Stock']['total_price']/$current['Stock']['stock'];
				
				if(!$this->save($current)){
					$error = true;
					$this->log('Failed saving updating item :'.print_r($item,true), 'errors');
				}else{
					// log transaction
					$id = $current['Stock']['id'];
					$trans['Transaction'] = array(
						'stock_id'		=> $id,
						'price'			=> $item['price'],
						'total_price' 	=> $item['price']*$item['stock'],
						'qty'			=> $item['stock'],
						'type'			=> 'add'
					);
								
					$this->Transaction->save($trans);
				}
			}
		}
		
		if(isset($error)){
			return false;
		}
		
		return true;
		
		
	}
	
	
	function sales($items, $seller){
		App::import('model', 'Category');
		$category = new Category();
				
		foreach($items['Stock'] as $item){
			
			// by pass wrong entry
			if($item['status'] == 'Not enough!' || $item['status'] == 'Not exist!') continue;
			
			$conditions = array('Stock.cat_id' => $item['cat_id'],
										'Stock.model' => $item['model'],
										'Stock.colour' => $item['colour']);

			if(!empty($item['size'])){
				$conditions['Stock.size'] = $item['size'];
			}

			// get the item
			$current = $this->find('first', array(
				'conditions'		=> $conditions,
			));
			
			// update stock
			$current['Stock']['stock'] = $current['Stock']['stock'] - $item['stock'];
			$current['Stock']['total_price'] = $current['Stock']['stock']*$current['Stock']['price'];
			
			// don't let stock negative
			if($current['Stock']['stock'] < 0){
				$current['Stock']['stock'] = 0;
				$current['Stock']['total_price'] = 0;
			}

			if($this->save($current)){
				// save transaction
				$margin = $item['price'] - $current['Stock']['price'];
				$total_margin = $margin * $item['stock'];
				
				$trans['Transaction'] = array(
					'stock_id'		=> $current['Stock']['id'],
					'price'			=> $item['price'],
					'total_price' 	=> $item['price']*$item['stock'],
					'qty'			=> $item['stock'],
					'type'			=> 'sales',
					'margin'		=> $margin,
					'total_margin'	=> $total_margin,
					'date'			=> $item['date'],
					'user_id'		=> $seller
				);
				
				$this->Transaction->create();
				if(!$this->Transaction->save($trans)){
					// log for errors
					$this->log('Failed inserting sales :'.print_r($trans,true), 'errors');
					$error = true;
				}

			}else{
				// log failed sales
				$this->log('Failed updating sales :'.print_r($current,true), 'errors');
				$error = true;
			}



		}
		
		if(isset($error)){
			return false;
		}
		
		return true;
		
	}
	
	
	
	function book($data){
		$conditions = array('Stock.cat_id' => $data['Stock']['cat_id'],
									'Stock.model' => $data['Stock']['model'],
									'Stock.colour' => $data['Stock']['colour']);
		
		if(!empty($data['Stock']['size'])){
			$conditions['Stock.size'] = $data['Stock']['size'];
		}
		
		// get the item
		$current = $this->find('first', array(
			'conditions'		=> $conditions,
		));
		
		if(!empty($current) && ($current['Stock']['stock'] >= $data['Stock']['stock'])){
			$current['Stock']['booked'] = $current['Stock']['booked'] + $data['Stock']['stock'];
			
			
			if($this->save($current)){
				// save transaction
				$trans['Transaction'] = array(
					'stock_id'		=> $this->id,
					'price'			=> $data['Stock']['price'],
					'total_price' 	=> $data['Stock']['price']*$data['Stock']['stock'],
					'qty'			=> $data['Stock']['stock'],
					'type'			=> 'book'
				);
				$this->Transaction->save($trans);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	
	function cdelete($id){
		$this->id = $id;
		
		// marked user as deleted
		return $this->saveField('deleted',1);
	}
}