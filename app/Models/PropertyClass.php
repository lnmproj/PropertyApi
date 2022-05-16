<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyClass extends Model
{
    use HasFactory;
    protected $table="property_classification";
    protected $guarded=[];
}
