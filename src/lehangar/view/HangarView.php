<?php

namespace lehangar\view;

class HangarView extends \mf\view\AbstractView
{

    public function __construct( $data ){
        parent::__construct($data);
    }

    protected function renderBody($selector)
    {
        /**
         * Switch pour le choix du render.
         */
        $html = $this->renderHeader();
        switch ($selector){
            case 'home':
                $html .= $this->renderHome();
                break;
            case 'cart':
                $html .= $this->renderCart();
                break;
        }
        return $html;
    }

    protected function renderHeader(){
        return "
            <header>
                <div>
                    <h1>LeHangar.local 🥕</h1>
                </div>
                <nav>
                    <a href='#'>Accueil</a>
                    <a href='#'>Producteurs</a>
                    <a href='#'>Panier</a>
                </nav>
            </header>
        ";
    }

    protected function renderCart(){
        $prixTotal = 0;
        $html = "
            <section>
                <h2>Votre panier 🛒:</h2>
                <div>
                    <div> <!-- div avec overflow: scroll -->
                        ";

                    foreach ($this->data as $article){
                        $prixArticle = $article->prix * $article->quantite;
                        $prixTotal += $prixArticle;
                        $html .= "
                            <div>
                                <div>
                                    <p>Quantité: $article->quantite</p>
                                </div>
                                <div>
                                    <p>Produit : $article->nom</p>
                                    <p>Prix : $article->prix</p>
                                    <p>Producteur : $article->nom</p>
                                </div>
                                <div>
                                    <p>Total: $prixArticle</p>
                                </div>
                            </div>
                        ";
                    }

        $html .="
                    </div>
                    
                    <div>
                        <p>Total: $prixTotal €</p>
                        <a href='#'>Valider</a>
                    </div>
                </div>
            </section>
        ";

        return $html;
    }
}