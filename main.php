<?php

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
$r = new \mf\router\Router();
$r->addRoute('accueil', '/accueil/', '\lehangar\control\HangarController', 'viewProduit', Authentification::ACCESS_LEVEL_NONE);
$r->addRoute('producteurs', '/producteurs/', '\lehangar\control\HangarController', 'viewProd', Authentification::ACCESS_LEVEL_NONE);
$r->setDefaultRoute('/home/');
$r->run();