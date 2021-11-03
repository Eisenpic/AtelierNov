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
}