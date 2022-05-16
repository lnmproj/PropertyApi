<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    protected $table="property";
    protected $guarded=[];

   public function PropertyFloor(){
    return $this->hasMany('App\Models\PropertyFloor', 'property_id','id');   
   }

    public function PropertyClass(){
        return $this->belongsTo('App\Models\PropertyClass', 'property_classification_id','property_classification_id');
    }
    public function PropertyType(){
        return $this->hasOne('App\Models\PropertyType','property_type_id','property_type_id');
    }

    public function ProductCategory(){
        return $this->belongsTo('App\Models\ProductCategory', 'product_category_id','product_category_id');
    }
    public function Town(){
        return $this->hasOne('App\Models\Town', 'town_id','province_id');
    }

    public function Province(){
        return $this->hasOne('App\Models\Province', 'province_id','province_id');
    }
    public function PropertyImages(){
        return $this->hasMany('App\Models\PropertyImages','property_id','id');
    }
}
