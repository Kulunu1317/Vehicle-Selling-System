<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'advertisement_id',
        'owner_id',
        'message', // Any extra details the owner sends
    ];

    public function advertisement() {
        return $this->belongsTo(Advertisement::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }
}