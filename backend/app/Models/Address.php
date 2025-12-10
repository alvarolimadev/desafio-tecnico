<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Address extends Model {
    protected $fillable = ['street','number','complement','neighborhood','city','state','zip','country'];

    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
