<?php

namespace lehangar\model;


use Illuminate\Database\Eloquent\Model;

class Commande extends Model {
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $timestamps = false;
}