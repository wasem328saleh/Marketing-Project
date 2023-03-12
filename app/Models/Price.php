<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Price extends Model implements JWTSubject
{
    use HasFactory;
    protected $table = 'prices';
    protected $primaryKey = 'id';
    protected $fillable = [
        's1',
        'd1',
        's2',
        'd2',
        's3',
        'product_id'
    ];
    /**
     * Get the product that owns the Price
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
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
