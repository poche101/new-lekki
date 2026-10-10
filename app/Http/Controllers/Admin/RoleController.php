<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index', [
            'permissions' => config('roles.permissions'),
            'labels' => config('roles.labels'),
            'permKeys' => ['homepage' => 'Manage homepage', 'media' => 'Manage media', 'content' => 'Manage content', 'ads' => 'Manage ads', 'moderate' => 'Moderate comments', 'members' => 'Manage members', 'notify' => 'Send notifications', 'analytics' => 'View analytics'],
        ]);
    }

    /**
     * The role/permission map ships in config/roles.php (versioned with the
     * code, reviewed like any other change). This stores an override in the
     * database so it can be edited without a deploy; EnsureRole checks the
     * override first, falling back to the config file.
     */
    public function update(Request $request)
    {
        $overrides = [];
        foreach ($request->input('perms', []) as $role => $keys) {
            if ($role === 'super_admin') {
                continue;
            }
            // Filter out the hidden "" marker the form always sends per role,
            // so unchecking every box for a role correctly saves an empty list
            // instead of the role's key being absent from the request entirely.
            $overrides[$role] = array_values(array_filter($keys, fn ($k) => $k !== ''));
        }
        Setting::put('role_permission_overrides', $overrides);

        return back()->with('status', 'Roles updated.');
    }
}
