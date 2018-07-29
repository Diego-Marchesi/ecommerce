<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Product;

$app->get('/admin/products', function() {

	
	User::verifyLogin();

	$products = Product::listAll();

     $page = new PageAdmin();

	$page->setTpl("products", array(
		"products"=>$products
	));
   
	exit;

});




$app->get('/admin/products/create', function() {

	
	User::verifyLogin();


     $page = new PageAdmin();

	$page->setTpl("products-create");
   
	exit;

});

$app->post('/admin/products/create', function() {

	
	User::verifyLogin();

	$product = new Product();

	$product->setData();

	$product->save();

	header("Location: /admin/products");

	exit;
     

});


?>