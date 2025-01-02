<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Events\ActivityCompleted;
use App\Events\UserCreated;
use App\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasDefaultTenant, HasTenants
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    use HasRoles;
    /*
        Роли, у которых есть доступ к панели filament Control ['xxx|xxx']
        По умолчанию доступ отключён
    */
    const roleCanUseControlPanel = 'Root|Admin|Control';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    protected $touches = ['teams'];

    /**
 * Роли пользователя.
 */
public function roles(): BelongsToMany
{
    return $this->belongsToMany(Role::class);
}

/* Доступ к панели управления Filament (роли)  */
public function canAccessPanel(Panel $panel): bool
{
    if ($panel->getId() === 'control') {
        return $this->hasRole(self::roleCanUseControlPanel);
    }

    return false;

}

    /* Методы для работы с teams */
public function teams(): BelongsToMany
{
    return $this->belongsToMany(Team::class);
}

public function getTenants(Panel $panel): Collection
{
    return $this->teams;
}

public function canAccessTenant(Model $tenant): bool
{
    return true;//$this->teams()->whereKey($tenant)->exists();
}

public function getDefaultTenant(Panel $panel): ?Model
{
    return $this->currentTeam;
}

public function currentTeam(): BelongsTo
{
    return $this->belongsTo(Team::class, 'current_team_id');
}

public function isCurrentTeam(Team $team){
    return $this->currentTeam() === $team;
}

public function switchTeam($team){

    $this->forceFill([
        'current_team_id' => $team->id,
    ])->save();

    $this->setRelation('currentTeam', $team);

    return true;
}


/**
 * Регистрация активности по событиям модели.
*/
protected static function booted(): void
{
    static::created(function (User $user) {
        ActivityCompleted::dispatch('created',$user);
        UserCreated::dispatch($user);
    });
    static::updated(function (User $user) {
        ActivityCompleted::dispatch('updated',$user);
    });
    static::deleting(function (User $user) {
        ActivityCompleted::dispatch('deleted',$user);
    });
}

}
