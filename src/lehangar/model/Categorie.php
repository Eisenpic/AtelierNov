<?php

namespace lehangar\model;


use Illuminate\Database\Eloquent\Model;

class Categorie extends Model {
    protected $table = 'categorie';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function produits(){
        return $this->hasMany('lehangar\model\Produit', "categorie_id");
    }
}
