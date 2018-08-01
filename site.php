<?php

use \Hcode\Page;
use \Hcode\Model\Product;

$app->get('/', function() {
    
    $products = new Product();

    $products = Product::listAll();

	//echo "OK";
	$page = new Page();
	$page->setTpl("index", array(
		"products"=>Product::checkList($products)
	));

});

?>