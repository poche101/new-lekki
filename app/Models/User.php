<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'groups', 'is_active', 'last_active_at'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['groups' => 'array', 'is_active' => 'boolean', 'last_active_at' => 'datetime'];

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Does this user's role carry the given permission key? See config/roles.php.
     * Named hasPermission() rather than overriding can(): Blade's @can directive
     * and the authorize() helper go through the Gate facade, not through a
     * model override of can() — so this is wired up via Gate::define() in
     * AppServiceProvider, which is what @can, $user->can() and EnsureRole all
     * end up calling. Overriding can() directly would silently break @can.
     */
    public function hasPermission(string $ability): bool
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        $overrides = Setting::get('role_permission_overrides', []);
        $perms = $overrides[$this->role] ?? config('roles.permissions.' . $this->role, []);

        return in_array($ability, $perms, true);
    }

    public function roleLabel(): string
    {
        return config('roles.labels.' . $this->role, ucfirst($this->role));
    }

    public function initials(): string
    {
        $name = preg_replace('/^(Pastor|Deacon|Sister|Brother)\s+/', '', $this->name);
        $parts = explode(' ', trim($name));

        return strtoupper(substr($parts[0] ?? '', 0, 1) . substr($parts[1] ?? '', 0, 1));
    }
}
