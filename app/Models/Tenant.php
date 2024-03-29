<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'status',
        'name',
        'domain',
        'database',
        'image',
        'system_settings',
        'settings',
        'user_id',
        'subscription_id',
        'tenant_request_id',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'status' => 'boolean',
        'system_settings' => 'array',
        'settings' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function tenantRequest()
    {
        return $this->belongsTo(TenantRequest::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
