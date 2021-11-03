<?php

namespace lehangar\controller;

use lehangar\model\Produit;
use lehangar\model\Producteur;
use lehangar\model\Categorie;
use lehangar\view\HangarView;
use mf\control\AbstractController;
use mf\router\Router;

class HangarController extends AbstractController
{
    public function construct() {
        parent::__construct();
    }

    public function viewProduit(){
        $produits = Produit::select()->get();
        $view = new HangarView($produits);
        $view->render('produit');
    }
}