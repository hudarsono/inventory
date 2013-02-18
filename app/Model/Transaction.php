<?php
App::import('Security');
App::import('Sanitize');

class Transaction extends AppModel{
	var $name = "Transaction";
	var $actsAs = array('Containable');
	
	var $belongsTo = array(
        'Stock' =>
            array(
                'className'              => 'Stock',
                'foreignKey'             => 'stock_id',
                'unique'                 => true,
            ),
		'User' =>
            array(
                'className'              => 'User',
                'foreignKey'             => 'user_id',
                'unique'                 => false,
            ),
    );
}