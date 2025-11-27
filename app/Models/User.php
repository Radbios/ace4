<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    public $keyType = "string";
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'cpf',
        'name',
        'email',
        'password',
        'phone',
        'cep',
        'city',
        'state',
        'country',
        'is_activate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getCpfAttribute(string $value)
    {
        $value = preg_replace('/\D/', '', $value);

        if (strlen($value) !== 11) {
            return $value;
        }

        return substr($value, 0, 3) . '.' .
            substr($value, 3, 3) . '.' .
            substr($value, 6, 3) . '-' .
            substr($value, 9, 2);
    }

    public function getCreatedAtAttribute(string $value)
    {
        return date('d/m/Y H:i:s', strtotime($value));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function collaborators()
    {
        return $this->hasMany(Collaborator::class, 'user_id');
    }

    public function my_collaborations()
    {
        return $this->hasMany(Collaborator::class, 'user_id')->where('user_id', $this->id);
    }

    public function coordinator_projects()
    {
        return $this->hasMany(Project::class, 'coordinator_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function has_role(int $role)
    {
        return $this->role_id === $role;
    }
}
