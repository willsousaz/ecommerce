<?php

use \Ytech\Page; 
use \Ytech\Model\Product;

$app->get('/',function(){

	$products =  Product::listAll();

	$page = new Page();

	$page->setTpl("index",[
		"products"=>Product::checklist($products)
	]); 

});


 ?>