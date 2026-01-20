<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];



    public function pharmacies():BelongsToMany
    {
        // this means the relation many to many (pivot table named pharmacy_product)
        return $this->belongsToMany(Pharmacy::class)
            ->withPivot(['price', 'quantity'])
            ->withTimestamps();
    }


    public function getImageUrlAttribute(){
        return $this->image ? asset('storage/' . $this->image) : asset('images/default.jpg');
    }

    public function scopeSearch($query, $search){
        return $query->where('title', 'LIKE', "%{$search}%");
    }
}
