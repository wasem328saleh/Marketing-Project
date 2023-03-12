<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Product extends Model implements JWTSubject
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'o_price',
        's_price',
        'description',
        'phone_number',
        'exp_date',
        'image_url',
        'quantity',
        'category',
        's1',
        'Counter_days1',
        's2',
        'Counter_days2',
        's3',
        'count_views',
        'count_comments',
        'count_likes',
        'user_id',
        'category_id'
    ];
    /**
     * Get the user that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    /**
     * Get all of the category for the Product
     *
     * @return \Illuminate\Databaategoryquent\Relations\HasMany
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    /**
     * Get the price associated with the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function price()
    {
        return $this->hasOne(Price::class, 'Product_id', 'id');
    }
    /**
     * Get all of the comments for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment()
    {
        return $this->hasMany(Comment::class, 'product_id', 'id');
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'product_id', 'id');
    }
    /**
     * Get all of the comments for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function like()
    {
        return $this->hasMany(Like::class, 'like_id', 'id');
    }
    /**
     * Get the user that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /**
     * Get the user that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    // Rest omitted for brevity
    /** * Get the identifier that will be stored in the subject claim of the JWT.
     * * @return mixed
     */
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
