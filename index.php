<?php 

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	//echo "OK";
	$page = new Page();
	$page->setTpl("index");

});

$app->get('/admin', function() {
    
	//echo "OK";
	$page = new PageAdmin();
	$page->setTpl("index");

});

$app->run();

 ?>