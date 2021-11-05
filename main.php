<?php

use lehangar\view\HangarView;
use mf\utils\ClassLoader;
use \mf\auth\Authentification;

require_once 'vendor/autoload.php';
require_once 'src/mf/utils/AbstractClassLoader.php';
require_once 'src/mf/utils/ClassLoader.php';


$loader = new ClassLoader('src');
$loader->register();


$config = parse_ini_file("config.ini");

/* une instance de connexion  */
$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

session_start();

//initialisation du panier si il n'existe pas
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}
$r = new \mf\router\Router();
$r->addRoute('accueil', '/accueil/', '\lehangar\control\HangarController', 'viewProduit', Authentification::ACCESS_LEVEL_NONE);
$r->addRoute('ajouterPanier', '/ajouterPanier/', '\lehangar\control\HangarController', 'addToCart', Authentification::ACCESS_LEVEL_NONE);
$r->addRoute('producteurs', '/producteurs/', '\lehangar\control\HangarController', 'viewProd', Authentification::ACCESS_LEVEL_NONE);
$r->addRoute('panier', '/panier/', '\lehangar\control\HangarController', 'viewCart', Authentification::ACCESS_LEVEL_NONE);
$r->addRoute('coord', '/coord/', '\lehangar\control\HangarController', 'viewCoord', Authentification::ACCESS_LEVEL_NONE);
$r->addRoute('confirm', '/confirm/', '\lehangar\control\HangarController', 'viewConfirm', Authentification::ACCESS_LEVEL_NONE);
$r->addRoute('sendCoord', '/sendCoord/', '\lehangar\control\HangarController', 'sendCoord', Authentification::ACCESS_LEVEL_NONE);
$r->addRoute('view', '/view/', '\lehangar\control\HangarController', 'viewArticle', Authentification::ACCESS_LEVEL_NONE);
$r->addRoute('supprPanier', '/supprPanier/', '\lehangar\control\HangarController', 'supprPanier', Authentification::ACCESS_LEVEL_NONE);
$r->addRoute('viewproducteur', '/viewproducteur', '\lehangar\control\HangarController', 'viewDetailProducteur', Authentification::ACCESS_LEVEL_NONE);
$r->setDefaultRoute('/accueil/');
$r->run();
