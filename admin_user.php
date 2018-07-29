<?php


use \Hcode\PageAdmin;
use \Hcode\Model\User;


//executa arquivo users.html dentro da views
$app->get('/admin/users', function() {
    
	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();
	$page->setTpl("users", array(
		"users"=>$users
	));

});


//executa arquivo users-create.html dentro da views
$app->get('/admin/users/create', function() {
    
	User::verifyLogin();
    
	$page = new PageAdmin();
	$page->setTpl("users-create");

});

//apagar do sistema 
$app->get('/admin/users/:iduser/delete', function($iduser) {
    
	User::verifyLogin();

	$user = new user();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;


    
	

});


//executa arquivo users-update.html dentro da views
$app->get('/admin/users/:iduser', function($iduser) {
    
	User::verifyLogin();
    
	$user = new User();
	$user->get((int)$iduser);

	$page = new PageAdmin();
	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});

//recebe os dado em post em envia para o banco de dados
$app->post('/admin/users/create', function() {
    
	User::verifyLogin();

	//var_dump($_POST);//testa se os dados enviados do formulario de cadastro estão chegando
	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;

  	

});


//salvar edição
$app->post('/admin/users/:iduser', function($iduser) {
    
	User::verifyLogin();
    
	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;
});



?>