<?php

// Single source of truth for roles and what each one can do.
// Checked via $user->can('media') etc. (see User::can() override) and the
// EnsureRole middleware (role:media). Super Admin implicitly has everything.
return [
    'permissions' => [
        'super_admin'     => ['homepage', 'media', 'content', 'ads', 'moderate', 'members', 'notify', 'analytics', 'roles'],
        'admin'           => ['homepage', 'media', 'content', 'ads', 'moderate', 'members', 'notify', 'analytics'],
        'content_manager' => ['content'],
        'media_manager'   => ['media'],
        'moderator'       => ['moderate'],
        'member_manager'  => ['members'],
        'analyst'         => ['analytics'],
    ],
    'labels' => [
        'super_admin' => 'Super Admin', 'admin' => 'Admin', 'content_manager' => 'Content Manager',
        'media_manager' => 'Media Manager', 'moderator' => 'Moderator', 'member_manager' => 'Member Manager',
        'analyst' => 'Analyst', 'leader' => 'Leader', 'member' => 'Member',
    ],
];
