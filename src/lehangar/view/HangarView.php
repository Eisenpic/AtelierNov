<?php

namespace lehangar\view;

use mf\view\AbstractView;

class HangarView extends AbstractView
{
    protected function renderBody($selector)
    {
        switch($selector) {
            case 'producteur':
                $html = $this->renderProducteur();
                break;
        }
        return $html;
    }

    private function renderProducteur(){
        $html = '<div>
                    <section>
                        <h1>Découvrez nos producteurs ! 🌾</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque quis lorem ut purus posuere tempus nec at ante. Suspendisse ornare pulvinar pellentesque. Nullam sed viverra velit. Aliquam et nulla ut leo accumsan viverra nec non sapien. Suspendisse vel sapien leo. Nullam convallis ultricies nibh, vel facilisis arcu efficitur vel. Sed sapien risus, lacinia vitae lacus eget, porttitor tempor nibh.</p>
                    </section>';
        foreach ($this->data as $producteur) {
            $html .= '<section>
                        <img src="https://www.tutorialspoint.com/assets/profiles/13558/profile/60_76068-1512713229.jpg">
                        <p>' . $producteur->nom . '</p>
                        <p>' . $producteur->mail . '</p>
                        <p>' . $producteur->localisation . '</p>
                      </section>';
        }
        $html .= '</div>';
        return $html;
    }
}