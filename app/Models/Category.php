<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Category extends Model implements JWTSubject
{
    use HasFactory;
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];

    // Rest omitted for brevity
    /** * Get the identifier that will be stored in the subject claim of the JWT.
     * * @return mixed
     */
    /**
     * Get all of the comments for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    /**
     * Get the user that owns the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
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
