<?php
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

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



// criando seleção por categoria 
$app->get('/categories/:idcategory', function($idcategory) {

	
	$category = new Category();

	$category->get((int)$idcategory);

	 $page = new Page();

	$page->setTpl("category", array(
		"category"=>$category->getValues(),
		"products"=>array()
	));
	        
	exit;

});


?>