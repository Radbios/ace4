<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'category',
        'coordinator_id',
        'email',
        'is_subscription',
        'max_collaborators',
    ];

    public function coordinator()
    {
        return $this->belongsTo(User::class, "coordinator_id");
    }

    public function collaborators()
    {
        return $this->hasMany(Collaborator::class, "project_id")->with('user');
    }
}
