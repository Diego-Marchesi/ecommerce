<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

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

$app->run();

 ?>