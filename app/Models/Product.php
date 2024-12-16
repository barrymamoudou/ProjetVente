<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

     protected $guarded = [];

      //un product appartient a une seule categorie donne 
     public function category(){
        
        return $this->belongsTo(Category::class,'category_id','id');
    }
    //un product appartient a seul fourniseur 
    public function supllier(){
        
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
}
