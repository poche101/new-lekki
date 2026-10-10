<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $role = $request->get('role', 'all');

        return view('admin.members.index', [
            'members' => User::when($q, fn ($w) => $w->where('name', 'like', "%$q%")->orWhere('email', 'like', "%$q%"))
                ->when($role !== 'all', fn ($w) => $w->where('role', $role))->orderBy('name')->paginate(15)->withQueryString(),
            'roles' => config('roles.labels'), 'q' => $q, 'role' => $role,
            'meetings' => Meeting::orderBy('starts_at')->get(),
            'groups' => ['Leaders', 'Couples', 'Youth', 'Media', 'All members'],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:150'], 'email' => ['required', 'email', 'unique:users,email'], 'role' => ['required', 'string']]);

        $user = User::create($data + ['password' => Hash::make(Str::random(24))]);
        Password::sendResetLink(['email' => $user->email]); // acts as the invite email

        return back()->with('status', 'Invite sent to ' . $user->email);
    }

    public function toggle(User $member)
    {
        $member->update(['is_active' => ! $member->is_active]);

        return back()->with('status', $member->name . ' is now ' . ($member->is_active ? 'active' : 'inactive'));
    }

    public function resetPassword(User $member)
    {
        Password::sendResetLink(['email' => $member->email]);

        return back()->with('status', 'Password reset link sent to ' . $member->email);
    }

    public function meetingAccess(Request $request, Meeting $meeting)
    {
        $groups = (array) $request->input('access_groups', []);
        $meeting->update(['access_groups' => $groups]);

        return back()->with('status', 'Meeting access updated.');
    }
}
