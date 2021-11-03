<?php

namespace lehangar\model;


use Illuminate\Database\Eloquent\Model;

class Commande extends Model {
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function items(){
        return $this->belongsToMany(Produit::class, 'contenu', 'commande_id' , 'prod_id')->withPivot("quantite");
    }
}