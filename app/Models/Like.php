<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Like extends Model implements JWTSubject
{
    use HasFactory;
    protected $table = 'likes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'status',
        'product_id',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /** * Return a key value array, containing any custom claims to be added to the JWT. * * @return array */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
