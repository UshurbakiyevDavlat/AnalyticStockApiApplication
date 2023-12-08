<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Annotations as OA;
use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\EncryptedRow;
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class User extends Authenticatable implements JWTSubject, CipherSweetEncrypted
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use UsesCipherSweet;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name',
        'email',
        'azure_token',
        'avatar_url',
    ];

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Get the custom claims array to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Configuration of Cipher sweet package encryption
     *
     * @param EncryptedRow $encryptedRow
     * @return void
     */
    public static function configureCipherSweet(EncryptedRow $encryptedRow): void
    {
        $encryptedRow
            ->addField('email')
            ->addField('name')
            ->addBlindIndex(
                'email',
                new BlindIndex('email_index'),
            )
            ->addBlindIndex(
                'name',
                new BlindIndex('name_index'),
            );
    }

    /**
     * Posts relationship
     *
     * @return BelongsToMany
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            'posts',
            'user_id',
            'post_id',
        );
    }

    /**
     * Bookmarks relationship
     *
     * @return BelongsToMany
     */
    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            'favourites',
            'user_id',
            'favouriteable_id',
            'id',
            'id',
        )
            ->where('favouriteable_type', Post::class);
    }

    /**
     * Likes relationship
     *
     * @return BelongsToMany
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            'likes',
            'user_id',
            'likeable_id',
            'id',
            'id',
        )
            ->where('likeable_type', Post::class);
    }

    /**
     * View relationship
     *
     * @return BelongsToMany
     */
    public function views(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            'post_views',
            'user_id',
            'post_id',
            'id',
            'id',
        );
    }

    /**
     * Subscriptions relationship
     *
     * @return BelongsToMany
     */
    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'post_category_subscription',
            'user_id',
            'category_id',
            'id',
            'id',
        );
    }
}
