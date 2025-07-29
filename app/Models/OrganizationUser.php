<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationUser extends Model
{
    use HasFactory;

    protected $table = 'organization_user';
    protected $fillable = [
        'user_id',
        'organization_id',
        'is_admin',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];
}
