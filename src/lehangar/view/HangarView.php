<?php

namespace lehangar\view;

use mf\view\AbstractView;

class HangarView extends AbstractView
{

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
            case 'produit';
                $html .= $this->renderProduit();
                break;
            case 'coord';
                $html .= $this->renderCoord();
                break;
        }
        return $html;
    }

    private function renderCoord(){
        return "<div>
                    <h2>Vos coordonnées ☎️ :</h2>
                    <div>
                        <form action='../sendCoord/' method='post'>
                                Prénom :<input type='text' name='prenom' required>
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
                </div>";
    }
}