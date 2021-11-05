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

    //affiche tous les produits sur la page d'accueil
    public function viewProduit(){
        //$produits = Produit::select()->orderBy('categorie_id')->get();
        $categorie = Categorie::select()->get();
        $view = new HangarView($categorie);
        $view->addStyleSheet('/html/css/accueil.css');
        $view->render('produit');
    }

    //affiche un article en détail
    public function viewArticle(){
        try {
            $res = Produit::where('id','=',$_GET['id'])->firstOrFail();
            $view = new HangarView($res);
            $view->addStyleSheet('/html/css/article.css');
            $view->render('view');
        } catch (ModelNotFoundException $e) {
            echo "Incorrect product number";
        }

    }

    public function viewProducteur(){
        $res = Producteur::where('id','=',$_GET['id'])->first();
        $view = new HangarView($res);
        $view->addStyleSheet('/html/css/detailproducteur.css');
        $view->render('viewproducteur');
    }


    //affiche la liste des producteurs

    public function viewProd(){
        $prod = Producteur::get();
        $view = new HangarView($prod);
        $view->addStyleSheet('/html/css/producteur.css');
        $view->render('producteur');

    }

    //affiche le panier
    public function viewCart(){
            $cart = $_SESSION['cart'];
            $view = new HangarView($cart);
            $view->addStyleSheet('/html/css/panier.css');
            $view->render('cart');
    }

    //ajoute un item dans le panier
    public function addToCart(){
        $quantite = filter_var($_POST['quantite'], FILTER_VALIDATE_INT);
        $produit = $_POST['produit'];
        $produit = Produit::select()->where("id", "=", $produit)->first();
        $prixLot = $produit->tarif_unitaire * $quantite;
        if($quantite != 0) {
            //si le panier n'est pas vide on vérifie que le produit qui va s'ajouter n'est pas déjà présent
            if (!empty($_SESSION['cart'])) {
                $compteur = 0;
                for ($i = 0; $i < count($_SESSION['cart']); $i++) {
                    //si on trouve un item avec le même id on donne à la variable compteur le n° de l'item dans le panier +1
                    //pour ne pas qu'il soit à 0 qui correspond au fait que le produit n'est pas dans le panier
                    if ($_SESSION['cart'][$i]['produit']['id'] == $produit['id']) {
                        $compteur = $i + 1;
                    }
                }

                //si 0 le produit n'est pas dans le panier on l'ajoute
                if ($compteur == 0) {
                    array_push($_SESSION['cart'], ['produit' => $produit, 'quantite' => $quantite, 'prixLot' => $prixLot]);

                    //sinon on récupère sa place dans le tableau panier grâce au compteur et on augmente la quantite et le prix du lot
                } else {
                    $_SESSION['cart'][$compteur - 1]['quantite'] += $quantite;
                    $_SESSION['cart'][$compteur - 1]['prixLot'] += $prixLot;
                }

                //sinon si le panier est vide on ajoute simplement sans vérifier
            } else {
                array_push($_SESSION['cart'], ['produit' => $produit, 'quantite' => $quantite, 'prixLot' => $prixLot]);
            }
        }
        header('Location: ../accueil/');
    }

    //affiche le formulaire qui permet de finaliser une commande
    public function viewCoord(){
        $view = new HangarView("");
        $view->addStyleSheet('/html/css/coord.css');
        $view->render('coord');
    }

    //affiche la page qui confirme la commande
    public function viewConfirm(){
        $view = new HangarView("");
        $view->render('confirm');
    }

    //envoi les données de la commande en BDD
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

        $_SESSION['commande'] = ['client' => ['nom' => $nom, 'email' => $email, 'telephone' => $tel], 'panier' => $_SESSION['cart']];

        //Réinitialise le panier
        $_SESSION['cart'] = [];

        header('Location: ../confirm/');
    }

    //supprime l'item demandé du panier
    public function supprPanier(){
        $id = $_GET['id'];
        unset($_SESSION['cart'][$id]);

        for ($i=$id; $i < count($_SESSION['cart']); $i++){
            $_SESSION['cart'][$i] = $_SESSION['cart'][$i+1];
            unset($_SESSION['cart'][$i+1]);
        }

        header('Location: ../panier/');
    }
}