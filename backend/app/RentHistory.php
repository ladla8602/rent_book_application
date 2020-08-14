<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RentHistory extends Model
{
    public $timestamps = true;
    protected $table = 'rent_history';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function book()
    {
        return $this->belongsTo('App\Book', 'book_id');
    }
}
