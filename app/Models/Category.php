<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'  
    ];
    public function relationsAsChild()
    {
        return $this->hasMany(Relation::class, 'child_id','id')->orderBy('distance','desc');
    }
    public function relationsAsParent()
    {
        return $this->hasMany(Relation::class, 'parent_id','id')->orderBy('distance','desc');
    }
}
