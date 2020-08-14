<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public $timestamps = true;
    protected $table = 'books';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
