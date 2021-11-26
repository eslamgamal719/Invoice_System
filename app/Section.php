<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    

    protected $fillable = [
        'section_name', 'description', 'created_by'
    ];


    public function products()
    {
        return $this->hasMany(Product::class, 'section_id', 'id');
    }
}
