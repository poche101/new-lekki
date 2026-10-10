<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = ['title', 'starts_at', 'room_url', 'access_groups'];
    protected $casts = ['starts_at' => 'datetime', 'access_groups' => 'array'];

    /** Can this user join, based on their groups overlapping access_groups? */
    public function isAccessibleBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }
        if (empty($this->access_groups) || in_array('All members', $this->access_groups, true)) {
            return true;
        }

        return count(array_intersect($this->access_groups, $user->groups ?? [])) > 0;
    }
}
