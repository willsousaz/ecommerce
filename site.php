<?php

use \Ytech\Page; 
use \Ytech\Model\Product;
use \Ytech\Model\Category;

$app->get('/',function(){

	$products =  Product::listAll();

	$page = new Page();

	$page->setTpl("index",[
		"products"=>Product::checklist($products)
	]); 

});

$app->get("/categories/:idcategory",function($idcategory){

		$category = new Category();

		$category->get((int)$idcategory);

		$page = new Page();

		$page->setTpl("category",[
			'category'=>$category->getValues(),
			'products'=>Product::checklist($category->getProducts())
		]);

	});

 ?>