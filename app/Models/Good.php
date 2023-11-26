<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use HasFactory;

    protected $guarded = false;
    public $timestamps = false;

    public function category() {
       return  $this->belongsTo(Category::class);

    }
    public function price()
    {
        return $this->belongsTo(Price::class);
    }

    public function review() {
        return $this->belongsTo(Reviews::class, 'item_rating');
    }
}
