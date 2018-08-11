<?php

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Category;
use \Hcode\Model\Cart;
use \Hcode\Model\Address;
use \Hcode\Model\User;

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

	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	$category = new Category();

	$category->get((int)$idcategory);

	$pagination = $category->getProductsPage($page);

	$pages  = [];

	for ($i=1; $i <= $pagination['pages']; $i++) { 
		array_push($pages, [
			"link"=>"/categories/".$category->getidcategory()."?page=".$i,
			"page"=>$i
		]);
	}

	 $page = new Page();

	$page->setTpl("category", array(
		"category"=>$category->getValues(),
		"products"=>$pagination["data"],
		"pages"=>$pages
	));
	        
	exit;

});



// criando caracteristicas do produto
$app->get('/products/:desurl', function($desurl) {

	$product = new Product();

	$product->getFromURL($desurl);

	 $page = new Page();

	$page->setTpl("product-detail", array(
		"product"=>$product->getValues(),
		"categories"=>$product->getCategories()
	));

});


// criando carinho de compra
$app->get('/cart', function() {
	
	$cart = Cart::getFromSession();

	 $page = new Page();

	$page->setTpl("cart", array(
		"cart"=>$cart->getValues(),
		"products"=>$cart->getProducts(),
		"error"=>Cart::getMsgError()

	));

});

// ADICIONANDO ITEM NO CARINHO
$app->get('/cart/:idproduct/add', function($idproduct) {
	
	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$qtd = (isset($_GET["qtd"]))? (int)$_GET["qtd"] : 1 ;

	for ($i=0; $i < $qtd ; $i++) { 
		$cart->addProduct($product);
	}

	

	header("Location: /cart");
	exit;

});


// rmover 1 ITEM NO CARINHO
$app->get('/cart/:idproduct/minus', function($idproduct) {
	
	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product);

	header("Location: /cart");
	exit;

});


// rmover todos os ITEM NO CARINHO
$app->get('/cart/:idproduct/remove', function($idproduct) {
	
	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product, true);

	header("Location: /cart");
	exit;

});




// rota para calcular frete
$app->post('/cart/freight', function() {
	
	$cart = Cart::getFromSession();

	$cart->setFreight($_POST["zipcode"]);

	header("Location: /cart");
	exit;
	 

});


// criando chckout para finalizar compra
$app->get('/checkout', function() {

	User::verifyLogin(false);

	$cart = Cart::getFromSession();

	$address = new Address();
	
	$page = new Page();

	$page->setTpl("checkout", array(
		"cart"=>$cart->getValues(),
		"address"=>$address->getValues()
	));

});


// criando pagina de login do site
$app->get('/login', function() {

	
	$page = new Page();

	$page->setTpl("login", array(
		"error"=>User::getError(),
		"errorRegister"=>User::getErrorRegister(),
		"registerValues"=>(isset($_SESSION['registerValues']))?$_SESSION['registerValues']: ["name"=>"", "email"=>"", "phone"=>""]
	));

});

// validando login site
$app->post('/login', function() {

	try{

	User::login($_POST['login'], $_POST['password']);
	} catch(Exception $e){
		User::setError($e->getMessage());
	}

	header("Location: /checkout");
	exit;

});




// fazendo logout
$app->get('/logout', function() {

	
	User::logout();

	header("Location: /login");
	exit;

});


$app->post('/register', function() {

	$_SESSION['registerValues'] = $_POST;
	

	if (!isset($_POST["name"]) || $_POST["name"] == '') {

		User::setErrorRegister("Preencha seu nome.");

		header("Location: /login");
		exit;
		
	}

	if (!isset($_POST["email"]) || $_POST["email"] == '') {

		User::setErrorRegister("Preencha seu e-mail.");

		header("Location: /login");
		exit;
		
	}

	if (!isset($_POST["password"]) || $_POST["password"] == '') {

		User::setErrorRegister("Preencha a senha.");

		header("Location: /login");
		exit;
		
	}

	if (User::checkLoginExist($_POST["email"]) === true) {

		User::setErrorRegister("Este endereço de e-mail já está sendo usado por outro usuário.");

		header("Location: /login");
		exit;
	}else{

	$user = new User();

	$user->setData(array(
		"inadmin" =>0,
		"desperson"=>$_POST["name"],
		"deslogin"=>$_POST["email"],
		"despassword"=>$_POST["password"],
		"desemail"=>$_POST["email"],
		"nrphone"=>$_POST["phone"]
	));

	$user->save();

	$user->login($_POST["email"], $_POST["password"]);

	header("Location: /checkout");
	exit;
	}
	

});


?>