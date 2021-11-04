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

    public function viewProduit(){
        $produits = Produit::select()->get();
        $view = new HangarView($produits);
        $view->render('produit');
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
  
    public function viewProd(){
        $prod = Producteur::get();
        $view = new HangarView($prod);
        $view->render('producteur');

    }

    public function viewCart(){
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
        array_push($_SESSION['cart'], [$produit, $quantite, $prixLot]);
        header('Location: ../accueil/');
    }

    public function viewCoord(){
        $view = new HangarView("");
        $view->render('coord');
    }

    public function viewConfirm(){
        $view = new HangarView("");
        $view->render('confirm');
    }

    public function sendCoord(){
        //Filtrage des données du formulaire
        $nom = filter_var(trim($_POST['nom']), FILTER_SANITIZE_STRING);
        $tel = filter_var(trim($_POST['tel']), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

        //Calculer le montant total du panier
        $montant = 0;
        foreach($_SESSION['cart'] as $cart) {
            $montant += $cart['prixLot'];
        }

        //Insertion de la commande
        $command = new Commande();
        $command->nom_client = $nom;
        $command->mail_client = $email;
        $command->tel_client = $tel;
        $command->montant = $montant;
        $command->etat = 0;
        $command->save();

        //Récupération du dernier INSERT dans la BDD
        $idCommande = $command->id;

        //Insertion des contenus pour chaques produits dans le panier
        foreach($_SESSION['cart'] as $produit){
            $contenu = new Contenu();
            $contenu->quantite = $produit['quantite'];
            $contenu->prod_id = $produit['produit']->id;
            $contenu->commande_id = $idCommande;
            $contenu->save();
        }
        //Réinitialise le panier
        $_SESSION['cart'] = [];
        header('Location: ../confirm/');
    }

}