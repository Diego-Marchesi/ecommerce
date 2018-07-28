<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	//echo "OK";
	$page = new Page();
	$page->setTpl("index");

});

$app->get('/admin', function() {
    
	User::verifyLogin();

	//echo "OK";
	$page = new PageAdmin();
	$page->setTpl("index");

});


$app->get('/admin/login', function() {
    
	//echo "OK";
	$page = new PageAdmin([
		"header"=>false,//desbilitando o heder e o footer
		"footer"=>false
	]);
	$page->setTpl("login");

});

//validando login
$app->post('/admin/login', function() {
    
	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;

});


//executa logout
$app->get('/admin/logout', function() {
    
	User::logout();
	header("Location: /admin/login");
	exit;

});

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


//executa arquivo forgot.html dentro da views tela de esqueci a senha
$app->get('/admin/forgot', function() {
    
    $page = new PageAdmin([
		"header"=>false,//desbilitando o heder e o footer
		"footer"=>false
	]);
	$page->setTpl("forgot");
	
	

});

//pega o email do esqueci minha senha enviado pelo usuario
$app->post('/admin/forgot', function() {
    
    $user = User::getForgot($_POST["email"]);
	
	header("Location: /admin/forgot/sent");
	exit;

});

//pega o email do esqueci minha senha enviado pelo usuario
$app->get('/admin/forgot/sent', function() {
    
     $page = new PageAdmin([
		"header"=>false,//desbilitando o heder e o footer
		"footer"=>false
	]);
	$page->setTpl("forgot-sent");
   
	exit;

});

//pega o email do esqueci minha senha enviado pelo usuario
$app->get('/admin/forgot/reset', function() {

	$user = User::validForgotDecrypt($_GET["code"]);
    
     $page = new PageAdmin([
		"header"=>false,//desbilitando o heder e o footer
		"footer"=>false
	]);
	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]

	));
   
	#exit;

});



//pega o email do esqueci minha senha enviado pelo usuario
$app->post('/admin/forgot/reset', function() {

	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"] , PASSWORD_DEFAULT, ["cost"=>12] );

	$user->setPassword($password);
    

	$page = new PageAdmin([
		"header"=>false,//desbilitando o heder e o footer
		"footer"=>false
	]);
	$page->setTpl("forgot-reset-success");

    
   	#exit;
});



//criando lista categorias 
$app->get('/admin/categories', function() {

	User::verifyLogin();

	$categories = Category::listAll();
    
     $page = new PageAdmin();

	$page->setTpl("categories", [
		"categories"=>$categories
	]);
   
	exit;

});


//criando creat categorias 
$app->get('/admin/categories/create', function() {

	
	User::verifyLogin();

     $page = new PageAdmin();

	$page->setTpl("categories-create");
   
	exit;

});



// salvando categoria criado no banco de dados 
$app->post('/admin/categories/create', function() {

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	        
   
	exit;

});



// deletando uma categoria criada 
$app->get('/admin/categories/:idcategory/delete', function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->delete();

	header("Location: /admin/categories");
	        
	exit;

});




// editando uma categoria criada 
$app->get('/admin/categories/:idcategory', function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	 $page = new PageAdmin();

	$page->setTpl("categories-update", array(
		"category"=>$category->getValues()
	));
	        
	exit;

});


// editando uma categoria criada 
$app->post('/admin/categories/:idcategory', function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	 $category->setData($_POST);

	 $category->save();


	 header("Location: /admin/categories");
	        
	exit;

});





$app->run();

 ?>