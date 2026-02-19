<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'max_ads',
        'expiry_time',
        'expiry_unit', // 'minutes', 'hours', 'days'
        'price',
        'tier', // 'normal', 'silver', 'gold', 'diamond'
        'image', // Path to the image in the public/assets folder
        'extra_questions', // JSON array of Admin's dynamic questions
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'extra_questions' => 'array',
        'price' => 'decimal:2',
        'max_ads' => 'integer',
        'expiry_time' => 'integer',
    ];

    /**
     * Get the HR purchased packages associated with this master package.
     */
    public function purchasedPackages(): HasMany
    {
        return $this->hasMany(HrPackage::class, 'package_id');
    }
}