<?php

namespace lehangar\view;

use lehangar\model\Categorie;
use lehangar\model\Commande;
use lehangar\model\Producteur;
use mf\router\Router;
use mf\utils\HttpRequest;


class HangarView extends \mf\view\AbstractView
{
    public function __construct($data)
    {
        parent::__construct($data);
    }

    protected function renderBody($selector)
    {
        /**
         * Switch pour le choix du render.
         */
        $html = $this->renderHeader();

        switch ($selector) {
            case 'producteur':
                $html .= $this->renderProducteur();
                break;
            case 'cart':
                $html .= $this->renderCart();
                break;
            case 'produit';
                $html .= $this->renderProduit();
                break;
            case 'coord';
                $html .= $this->renderCoord();
                break;
            case 'confirm';
                $html .= $this->renderConfirm();
                break;
            case 'view';
                $html .= $this->renderView();
                break;
            case 'viewproducteur':
                $html .= $this->renderViewProducteur();
                break;
        }

        $html .= $this->renderFooter();
        return $html;
    }

    private function renderView(){
        $r = new Router();
        $http_req = new HttpRequest();
        $html = "<section>
                    <div>
                        <div>
                        <img src='$http_req->root/html/img/product/". $this->data->img ."'>
                        </div>
                        <p>". $this->data->description ."</p>
                    </div>
                    <div>
                        <p>Produit : ". $this->data->nom ."</p>
                        <p>Type : " . $this->data->categorie->nom ."</p>
                        <p>Prix : ". $this->data->tarif_unitaire ."</p>
                        <p><span>Producteur : </span><a href=" . $r->urlFor('viewproducteur', ['id' => $this->data->producteur->id]) . "> ".$this->data->producteur->nom ."</a> </p>
                    </div>

                    <div>
                    <form action='../ajouterPanier/' method='post'>

                                    <input type='hidden' name='produit' value='". $this->data->id ."'>
                                    <select name='quantite'>
                                        <option value='0'>0</option>";

        for ($i = 1; $i < 21; $i++) {
            $html .= "<option value='$i'>$i</option>";
        }
        $html .= "</select>
                                        <input type='submit' value='Ajouter au panier'>
                                    </form>
                                </div>
                 </section>";
        return $html;
    }


    private function renderProducteur(){
        $http_req = new HttpRequest();
        $r = new Router();
        $html = '<section>
                    <div>
                        <h1>Découvrez nos producteurs ! 🌾</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque quis lorem ut purus posuere tempus nec at ante. Suspendisse ornare pulvinar pellentesque. Nullam sed viverra velit. Aliquam et nulla ut leo accumsan viverra nec non sapien. Suspendisse vel sapien leo. Nullam convallis ultricies nibh, vel facilisis arcu efficitur vel. Sed sapien risus, lacinia vitae lacus eget, porttitor tempor nibh.</p>
                    </div>';
        foreach ($this->data as $producteur) {
            if($producteur->nom != 'admin') {
                $html .= "<div>
                        <a href=" . $r->urlFor('viewproducteur', ['id' => $producteur->id]) . ">
                        <img src='$http_req->root/html/img/photo/$producteur->photo'>
                        <div>
                        <p>$producteur->nom</p>
                        <p>$producteur->mail</p>
                        <p>$producteur->localisation</p>
                      </div>
                      </div>";
                }
            }
        $html .= '</section>';

        return $html;
    }

    protected function renderHeader()
    {
        $r = new Router();
        return "
            <header>
                <div>
                    <h1>LeHangar.local 🥕</h1>
                </div>
                
                <!-- nav pc -->
                <nav>
                    <a href=". $r->urlFor('accueil', []) .">Accueil</a>
                    <a href=". $r->urlFor('producteurs', []). ">Producteurs</a>
                    <a href=". $r->urlFor('panier', []). ">Panier</a>
                </nav>
                
            </header>
        ";
    }

    protected function renderCart()
    {
        $prixTotal = 0;
        $r = new Router();
        $html = "
            <section>
                <h2>Votre panier 🛒 :</h2>
                <div>
                    <div> <!-- div avec overflow: scroll -->
                        ";

        foreach ($this->data as $key => $article) {
            $prixTotal += $article['prixLot'];

            $html .= "
                            <div>
                                <div>

                                    <p>Quantité:</p>
                                    <div>
                                        ". $article['quantite'] ."
                                    </div>
                                </div>
                                <div>
                                    <p><span>Produit : </span>". $article['produit']->nom ."</p>
                                    <p><span>Prix : </span>".$article['produit']->tarif_unitaire." €</p>
                                    <p><span>Producteur : </span>".$article['produit']->producteur->nom."</p>
                                </div>
                                <div>
                                    <p><span>Total: </span>". $article['prixLot'] ." €</p>
                                    <a href='". $r->urlFor('supprPanier', ['id' => $key]) ."'>Supprimer</a>
                                </div>
                            </div>
                        ";
        }


        $html .= "
                    </div>
                    ";

                    if (!empty($_SESSION['cart'])) {
                        $html .= "<div>
                            <p><span>Total: </span>$prixTotal €</p>
                            <a href=../coord/>Valider</a>
                        </div>";
                    } else {
                        $html .= "Votre panier est vide";
                    }

                    $html .= "
                </div>
            </section>
        ";

        return $html;
    }


    private function renderCoord(){
        if (!empty($_SESSION['cart'])) {
            $html = "<section>
                        <h2>Vos coordonnées ☎️ :</h2>
                        <div>
                            <form action='../sendCoord/' method='post'>
                                    Nom :<input type='text' name='nom' required>
                                    <br />
                                    Tel :<input type='number' name='tel' required>
                                    <br />
                                    Mail :<input type='email' name='email' required>
                                    <br />
                                <p>⚠️ Le paiement s'effectue lors du retrait de la commande</p>                    
                                <button type='submit'>Valider</button>
                            </form>
                        </div>
                    </section>";
            return $html;
        } else {
            header('Location: ../accueil/');
        }
    }

    private function renderConfirm()
    {
        $html = "
        <section>
                <h1>Votre commande a bien été enregistrée !</h1>
                <h2>Informations personnelles </h2>
            <div>
                <p><span>Nom: </span>". $_SESSION['commande']['client']['nom'] ."</p>
                <p><span>Email: </span>". $_SESSION['commande']['client']['email'] ."</p>
                <p><span>Telephone: </span>". $_SESSION['commande']['client']['telephone'] ."</p>
            </div>";

        $html .= "<h2>Produits dans la commande</h2>";

        $compteur = 1;
        foreach ($_SESSION['commande']['panier'] as $item){
            $html .= "
                <div>
                   <h3>Produit $compteur</h3>
                   <p><span>Nom: </span>". $item['produit']['nom'] ."</p>
                   <p><span>Quantité: </span>". $item['quantite'] ."</p>
                   <p><span>Producteur: </span>". $item['produit']['producteur']['nom'] ."</p>
                   <p><span>Prix du lot: </span>". $item['prixLot'] ." €</p>
                </div>
            ";
            $compteur++;
        }

        $html .= "</section>";
        return $html;
    }

    private function renderProduit()
    {
        $http_req = new HttpRequest();
        $html = "";
        $r = new Router();
        foreach ($this->data as $categorie) {
            if (count($categorie->produits) != 0) {
                $html .= "
                        <div id='categorieTitle'>
                            <h2>$categorie->nom</h2>
                        </div>
                        <section>";
                foreach ($categorie->produits as $produit) {
                    $html .= "
                                <div>
                                    <a href=" . $r->urlFor('view', ['id' => $produit->id]) . ">
                                        <div>
                                            <img src='$http_req->root/html/img/product/$produit->img'>
                                        </div>
                                        <div>
                                            <p>$produit->nom <br>$produit->tarif_unitaire €</p>
                                            <p><span>" . $produit->producteur->nom . "</span></p>
                                        </div>
                                    </a>
                                    <div>
                                        <form action='../ajouterPanier/' method='post'>
                                            <input type='hidden' name='produit' value='$produit->id'>
                                            <select name='quantite'>
                                                <option value='0'> 0 </option>";



                    for ($i = 1; $i < 21; $i++) {
                        $html .= "<option value='$i'>$i</option>";
                    }
                    $html .= "</select>
                                                        <input type='submit' value='Ajouter au panier'>
                                                    </form>
                                                </div>
                                        </div>
                            ";
                }
                $html .= "</section>";
            }
        }

        return $html;
    }

    private function renderViewProducteur()
    {
            $res = $this->data;
            $http_req = new HttpRequest();
            $html ="<section>
                    <div>
                        <div>
                        <img src='$http_req->root/html/img/photo/$res->photo'>
                        </div>
                    </div>
                    <div>
                        <p>Nom : $res->nom</p>
                        <p>Localisation: $res->localisation</p>
                        <p>Producteur : $res->mail</p>
                        <p>Mes produits : <ul>
                        ";
            $products = $res->produits;
            foreach($products as $p) {
                $html .="<li> <img src='$http_req->root/html/img/product/$p->img'</li>";
            }
                         $html .="</ul></p>
                    </div>
                 </section>";

            return $html;
    }

    protected function renderFooter()
    {
        $http_req = new HttpRequest();
        return "
            <footer>
                <div>
                    <img src='$http_req->root/html/img/wave.svg'>
                </div>        
            </footer>
        ";
    }
}
