<?php

namespace lehangar\model;


use Illuminate\Database\Eloquent\Model;

class Producteur extends Model {
    protected $table = 'producteur';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function produits(){
        return $this->hasMany(Produit::class, "prod_id");
    }
}