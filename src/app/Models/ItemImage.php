<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'path', 'order'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
