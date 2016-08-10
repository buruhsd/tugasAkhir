<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //
    protected $fillable = ['title','user_id', 'description'];

    public function user(){
    	return $this->belongsTo('App\User');
    }
}
