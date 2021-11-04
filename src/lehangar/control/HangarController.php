<?php

namespace lehangar\control;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use lehangar\model\Produit;
use lehangar\model\Producteur;
use lehangar\model\Categorie;
use lehangar\view\HangarView;
use mf\control\AbstractController;
use lehangar\model\Commande;
use lehangar\model\Contenu;
use mf\router\Router;

class HangarController extends AbstractController
{
    public function construct() {
        parent::__construct();
    }

    public function sendCoord(){
        $prenom = $_POST['prenom'];
        $nom = $_POST['nom'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        $prenom = filter_var(trim($prenom), FILTER_SANITIZE_STRING);
        $nom = filter_var(trim($nom), FILTER_SANITIZE_STRING);
        $tel = filter_var(trim($tel), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        //Calculer le montant total
        
        $montant = 0;
        $quantite = 0;
        //print_r($_SESSION['cart'][0]);
        foreach($_SESSION['cart'] as $cart) {
            $montant += $cart[2];
            //$quantite += ;
        }


        $command = new Commande();
        $command->nom_client = $nom;
        $command->prenom_client = $prenom;
        $command->mail_client = $email;
        $command->tel_client = $tel;
        $command->montant = $montant;
        $command->etat = 0;
        //$command->save();


        foreach($_SESSION['cart'] as $produit){
            $contenu = new Contenu();
            $contenu->quantite = $quantite;
            $contenu->
            $contenu->save();
            header('Location: home/');
        }

        //header('Location: /accueil/');
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

    public function viewCart(){
        /*$produit1 = Produit::select()->where("id", "=", "1")->get();
        array_push($_SESSION['cart'], [$produit1, 2, 4.6]);
        $produit2 = Produit::select()->where("id", "=", "2")->get();
        array_push($_SESSION['cart'], [$produit2, 1, 1.5]);*/
        if(!isset($_SESSION['cart'])){
            header('Location: ../accueil/');
        }
        else{
            $cart = $_SESSION['cart'];
            $view = new HangarView($cart);
            $view->render('cart');
        }
    }

    public function addToCart(){
        $quantite = filter_var($_POST['quantite'], FILTER_VALIDATE_INT);
        $produit = $_POST['produit'];
        $produit = Produit::select()->where("id", "=", $produit)->first();
        $prixLot = $produit->tarif_unitaire * $quantite;

        if (!empty($_SESSION['cart'])) {
            $compteur = 0;
            for ($i = 0; $i < count($_SESSION['cart']); $i++) {
                if ($_SESSION['cart'][$i]['produit']['id'] == $produit['id']) {
                    $compteur = $i+1;
                }
            }

            if ($compteur == 0) {
                array_push($_SESSION['cart'], ['produit' => $produit, 'quantite' => $quantite, 'prixLot' => $prixLot]);
                header('Location: ../accueil/');
            } else {
                $_SESSION['cart'][$compteur-1]['quantite'] += $quantite;
                $_SESSION['cart'][$compteur-1]['prixLot'] += $prixLot;
                header('Location: ../accueil/');
            }

        } else {
            array_push($_SESSION['cart'], ['produit' => $produit, 'quantite' => $quantite, 'prixLot' => $prixLot]);
            header('Location: ../accueil/');
        }
    }

    public function viewCoord(){
        $view = new HangarView("");
        $view->render('coord');
    }

    public function viewArticle(){
        try {
            $res = Produit::where('id','=',$_GET['id'])->firstOrFail();
            $view = new HangarView($res);
            $view->render('view');
        } catch (ModelNotFoundException $e) {
            echo "Incorrect product number";
        }

    }

    public function supprPanier(){
        $id = $_GET['id'];
        unset($_SESSION['cart'][$id]);

        for ($i=$id; $i < count($_SESSION['cart']); $i++){
            $_SESSION['cart'][$i] = $_SESSION['cart'][$i+1];
            unset($_SESSION['cart'][$i+1]);
        }

        header('Location: ../accueil/');
    }
}