<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $fillable = ['name','email','cpf','profile_id'];

    public function profile() {
        return $this->belongsTo(Profile::class);
    }

    public function addresses() {
        return $this->belongsToMany(Address::class)->withTimestamps();
    }
}