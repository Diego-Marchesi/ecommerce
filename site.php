<?php

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Category;

$app->get('/', function() {
    
    $products = new Product();

    $products = Product::listAll();

	//echo "OK";
	$page = new Page();
	$page->setTpl("index", array(
		"products"=>Product::checkList($products)
	));

});

// criando seleção por categoria 
$app->get('/categories/:idcategory', function($idcategory) {



	$category = new Category();

	$category->get((int)$idcategory);

	 $page = new Page();

	$page->setTpl("category", array(
		"category"=>$category->getValues(),
		"products"=>Product::checkList($category->getProducts())
	));
	        
	exit;

});


?>