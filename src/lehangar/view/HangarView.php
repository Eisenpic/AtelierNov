<?php

namespace lehangar\view;

use mf\view\AbstractView;

class HangarView extends AbstractView
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
            case 'produit';
                $html .= $this->renderProduit();
                break;
        }

        $html .= $this->renderFooter();

        return $html;
    }

    private function renderProduit()
    {
        $html = "
            <section>
                <div>
                    <div> <!-- div avec overflow: scroll -->
                        ";

        foreach ($this->data as $produit) {
            $html .= "
                            <div>
                                <div>
                                    <p>$produit->nom</p>
                                    <p>$produit->prix €</p>
                                    <p>$produit->producteur->nom</p>
                                    <form action='../AjouterPanier/' method='post'>
                                    <input type='hidden' name='produit' value='$produit'>
                                    <select name='quantite'>
                                        <option value=''>--Please choose an option--</option>";

            for ($i = 0; $i < 21; $i++){
                $html .= "<option value='$i'>$i</option>";
            }
            $html .= "</select>
                                        <input type='submit' value='Ajouter au panier'>
                                    </form>
                                </div>
                            </div>
                        ";
        }

        $html .= "
                    </div>
                </div>
            </section>
        ";

        return $html;
    }
}