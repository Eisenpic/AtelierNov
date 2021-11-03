<?php

namespace lehangar\control;

use lehangar\model\Producteur;
use lehangar\view\HangarView;
use mf\control\AbstractController;

class HangarController extends AbstractController
{
    public function construct() {
        parent::__construct();
    }

    public function viewProd(){
        $prod = Producteur::get();
        $view = new HangarView($prod);
        $view->render('producteur');
    }

    public function addToCart(){
        $quantite = filter_var($_POST['quantite'], FILTER_VALIDATE_INT);
        $produit = $_POST['produit'];
        if (isset($quantite)){
            $prixLot = $produit->tarif_unitaire * $quantite;
            array_push($_SESSION['cart'], [$produit, $quantite, $prixLot]);
        }
    }
}