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
}