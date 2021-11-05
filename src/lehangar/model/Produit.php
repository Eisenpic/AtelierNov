<?php

namespace lehangar\model;


use Illuminate\Database\Eloquent\Model;

class Produit extends Model {
    protected $table = 'produit';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function commandes(){
        return $this->belongsToMany(Commande::class, 'contenu', 'prod_id' , 'commande_id')->withPivot("quantite");
    }

    public function producteur(){
        return $this->belongsTo('lehangar\model\Producteur', 'prod_id');
    }

    public function categorie(){
        return $this->belongsTo('lehangar\model\Categorie', 'categorie_id');
    }

}