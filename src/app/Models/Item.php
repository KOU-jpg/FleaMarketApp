<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
    'user_id', 'condition_id', 'name', 'description', 'price'
    ];

    public function user()    {
        return $this->belongsTo(User::class);    }

    public function categories(){
    return $this->belongsToMany(Category::class, 'category_item');}

    public function condition()    {
        return $this->belongsTo(Condition::class);    }

    public function images()    {
        return $this->hasMany(ItemImage::class);    }

    public function comments(){
    return $this->hasMany(Comment::class);}

    public function favorites()    {
    return $this->hasMany(Favorite::class, 'item_id');}
    use HasFactory;
    
}
