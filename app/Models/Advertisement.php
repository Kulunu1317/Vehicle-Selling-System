<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Advertisement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // The Sales Company HR who posted it
        'hr_package_id', // The specific purchased package this ad belongs to
        'status', // 'pending', 'approved', 'rejected'
        'vehicle_data', // JSON array of all the form inputs (Brand, Date, Price, etc.)
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'vehicle_data' => 'array',
    ];

    /**
     * Get the HR User who owns this advertisement.
     */
    public function hrUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the specific purchased HR package this advertisement used.
     */
    public function hrPackage(): BelongsTo
    {
        return $this->belongsTo(HrPackage::class, 'hr_package_id');
    }

    /**
     * Get the submissions from Vehicle Owners for this specific advertisement.
     */
    public function ownerSubmissions(): HasMany
    {
        return $this->hasMany(OwnerSubmission::class, 'advertisement_id');
    }
}