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

        switch ($selector){
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
        }

        $html .= $this->renderFooter();
        return $html;
    }

    private function renderView(){
        $http_req = new HttpRequest();
            $html = "<section>
                    <div>
                        <div>
                        <img src='$http_req->root/html/img/". $this->data->img ."'>
                        </div>
                        <p>". $this->data->description ."</p>
                    </div>
                    <div>
                        <p>Produit : ". $this->data->nom ."</p>
                        <p>Type : " . $this->data->categorie->nom ."</p>
                        <p>Prix : ". $this->data->tarif_unitaire ."</p>
                        <p>Producteur : " . $this->data->producteur->nom ."</p>
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
        $html = '<section>
                    <div>
                        <h1>Découvrez nos producteurs ! 🌾</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque quis lorem ut purus posuere tempus nec at ante. Suspendisse ornare pulvinar pellentesque. Nullam sed viverra velit. Aliquam et nulla ut leo accumsan viverra nec non sapien. Suspendisse vel sapien leo. Nullam convallis ultricies nibh, vel facilisis arcu efficitur vel. Sed sapien risus, lacinia vitae lacus eget, porttitor tempor nibh.</p>
                    </div>';
        foreach ($this->data as $producteur) {
            $html .= '<div>
                        <img src="https://www.tutorialspoint.com/assets/profiles/13558/profile/60_76068-1512713229.jpg">
                        <p>' . $producteur->nom . '</p>
                        <p>' . $producteur->mail . '</p>
                        <p>' . $producteur->localisation . '</p>
                      </div>';
        }
        $html .= '</section>';

        return $html;
    }
    
    protected function renderHeader(){
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

    protected function renderCart(){
        $prixTotal = 0;
        $r = new Router();
        $html = "
            <section>
                <h2>Votre panier 🛒:</h2>
                <div>
                    <div> <!-- div avec overflow: scroll -->
                        ";

                    foreach ($this->data as $key => $article){
                        $prixTotal += $article['prixLot'];

                        $html .= "
                            <div>
                                <div>
                                    <p>Quantité: ". $article['quantite'] ."</p>
                                </div>
                                <div>
                                    <p>Produit : ". $article['produit']->nom ."</p>
                                    <p>Prix : ".$article['produit']->tarif_unitaire."</p>
                                    <p>Producteur : ".$article['produit']->producteur->nom."</p>
                                </div>
                                <div>
                                    <p>Total: ". $article['prixLot'] ."</p>
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
                            <p>Total: $prixTotal €</p>
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
                                    Téléphone :<input type='number' name='tel' required>
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

    private function renderConfirm(){
        $html = "
            <div>
                <h1>Votre commande a bien été enregistrée.</h1>
                <h2>Informations personnels</h2>
                <p>Nom: ". $_SESSION['commande']['client']['nom'] ."</p>
                <p>Email: ". $_SESSION['commande']['client']['email'] ."</p>
                <p>Telephone: ". $_SESSION['commande']['client']['telephone'] ."</p>
            </div>";

        $html .= "<h2>Produits dans la commande</h2>";

        $compteur = 1;
        foreach ($_SESSION['commande']['panier'] as $item){
            $html .= "
               <h3>Produit $compteur</h3>
               <p>Nom: ". $item['produit']['nom'] ."</p>
               <p>Quantité: ". $item['quantite'] ."</p>
               <p>Producteur: ". $item['produit']['producteur']['nom'] ."</p>
               <p>Prix du lot: ". $item['prixLot'] ." €</p>
            ";
            $compteur++;
        }
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
                                            <img src='$http_req->root/html/img/$produit->img'>
                                        </div>
                                        <div>
                                            <p>$produit->nom <br>$produit->tarif_unitaire €</p>
                                            <p>" . $produit->producteur->nom . "</p>
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
