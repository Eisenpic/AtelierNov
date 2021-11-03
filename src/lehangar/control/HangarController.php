<?php

namespace lehangar\controller;

use mf\control\AbstractController;
use lehangar\model\Commande;
use lehangar\model\Contenu;

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
        $email = filter_var(trim($email), FILTER_SANITIZE_STRING);

        //Calculer le montant total
        
        $montant;
        $quantite;
        foreach($_SESSION['cart'] as $cart) {
            $montant += $cart[2];
            $quantite += $cart;
        }     

        $command = new Commande();
        $command->nom_client = $nom;
        $command->prenom_client = $prenom;
        $command->mail_client = $email;
        $command->tel_client = $tel;
        $command->montant = $montant;
        $command->etat = 0;
        $command->save();

        /*
        foreach(){
            $contenu = new Contenu();
            $contenu->quantite = $quantite;
            $contenu->
            $contenu->save();
            header('Location: home/');
        }
        */
        header('Location: home/');
    }
}