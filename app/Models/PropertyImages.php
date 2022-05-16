<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyImages extends Model
{
    use HasFactory;
    protected $table="property_images";
    protected $guarded=[];

    public function Prodimage()
    {
        return $this->hasMany(Property::class,'id','property_id');
    }
}
