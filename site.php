<?php

use \Hcode\Page;

$app->get('/', function() {
    
	//echo "OK";
	$page = new Page();
	$page->setTpl("index");

});

?>