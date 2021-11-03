<?php

namespace lehangar\control;


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
  
    public function viewProd(){
        $prod = Producteur::get();
        $view = new HangarView($prod);
        $view->render('producteur');

    }
}