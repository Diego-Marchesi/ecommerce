<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Order;
use \Hcode\Model\OrderStatus;



// verificar status dos pedidos ordenados no administrador
$app->get('/admin/orders/:idorder/status', function($idorder) {
    
	User::verifyLogin();

	$order = new Order();

	$order->get((int)$idorder);

	$page = new PageAdmin();
	$page->setTpl("order-status", array(
		"order"=>$order->getValues(),
		"status"=>OrderStatus::listAll(),
		"msgError"=>Order::getError(),
		"msgSuccess"=>Order::getSuccess()
		
	));

	
});


// alterar status dos pedidos ordenados no administrador
$app->post('/admin/orders/:idorder/status', function($idorder) {
    
	User::verifyLogin();

	if (!isset($_POST['idstatus']) || !(int)$_POST['idstatus'] >0) {
		Order::setError("informe status atual.");
		header("Location: /admin/orders/".$idorder."/status");
		exit;
	}

	$order = new Order();

	$order->get((int)$idorder);

	$order->setidstatus((int)$_POST['idstatus']);

	$order->save();

	Order::setSuccess("Status atualizado.");
		header("Location: /admin/orders/".$idorder."/status");
		exit;

	
});




// deletar pedidos ordenados no administrador
$app->get('/admin/orders/:idorder/delete', function($idorder) {
    
	User::verifyLogin();

	$order = new Order();

	$order->get((int)$idorder);

	$order->delete();

	header("Location: /admin/orders");

	exit;
});



// detalhando o pedidos ordenados no administrador
$app->get('/admin/orders/:idorder', function($idorder) {
    
	User::verifyLogin();

	$order = new Order();

	$order->get((int)$idorder);

	$cart = $order->getCart();

	
	$page = new PageAdmin();
	$page->setTpl("order", array(
		"order"=>$order->getValues(),
		"cart"=>$cart->getValues(),
		"products"=>$cart->getProducts()
	));
});


// ordenar pedidos no administrador
$app->get('/admin/orders', function() {
    
	User::verifyLogin();

	

	$page = new PageAdmin();
	$page->setTpl("orders", array(
		"orders"=>Order::listAll()
	));

});

?>