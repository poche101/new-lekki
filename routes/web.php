<?php

use App\Http\Controllers\{BlogController, CommentController, ContactController, DepartmentController,
    GivingController, HigherLifeController, HomeController, LiveController, MeetingController, PrayerController,
    SearchController, StaticPageController};
use App\Http\Controllers\Auth\{AuthenticatedSessionController, RegisteredUserController};
use App\Http\Controllers\Admin\{AdController, AnalyticsController, CommentController as AdminCommentController,
    ContentController, DashboardController, DepartmentController as AdminDepartmentController,
    HomepageController, MediaController, MemberController, NotificationController, RoleController, SettingController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
| Every route below reads from the same tables the admin section writes to
| (App\Models\Post, Department, Ad, etc.) — there is no separate "demo data"
| for the public site vs the admin, unlike the earlier static HTML prototypes.
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/live', [LiveController::class, 'index'])->name('live.index');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

Route::get('/higher-life', [HigherLifeController::class, 'index'])->name('higher-life.index');
Route::get('/higher-life/{post:slug}', [HigherLifeController::class, 'show'])->name('higher-life.show');

Route::get('/fellowships', [DepartmentController::class, 'index'])->name('fellowships.index');
Route::get('/giving', [GivingController::class, 'redirect'])->name('giving.redirect');
Route::get('/prayer', [PrayerController::class, 'create'])->name('prayer.create');
Route::post('/prayer', [PrayerController::class, 'store'])->name('prayer.store');
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/pages/{slug}', [StaticPageController::class, 'show'])->name('pages.show');

Route::middleware('auth')->group(function () {
    Route::get('/meeting', [MeetingController::class, 'index'])->name('meeting.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Admin section
|--------------------------------------------------------------------------
| `auth` first (must be signed in), then per-route `role:<permission>` — see
| config/roles.php for which roles carry which permission, and
| App\Http\Middleware\EnsureRole for the check itself.
*/
Route::middleware(['auth', 'active'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:homepage')->group(function () {
        Route::get('/homepage', [HomepageController::class, 'edit'])->name('homepage.edit');
        Route::put('/homepage', [HomepageController::class, 'update'])->name('homepage.update');
    });

    Route::middleware('role:content')->group(function () {
        Route::resource('content', ContentController::class)->except(['show'])->parameters(['content' => 'post']);
        Route::resource('departments', AdminDepartmentController::class)->except(['show'])->parameters(['departments' => 'department']);
    });

    Route::middleware('role:media')->group(function () {
        Route::get('/media', [MediaController::class, 'index'])->name('media.index');
        Route::post('/media', [MediaController::class, 'store'])->name('media.store');
        Route::put('/media/{mediaItem}', [MediaController::class, 'update'])->name('media.update');
        Route::post('/media/{mediaItem}/archive', [MediaController::class, 'archive'])->name('media.archive');
    });

    Route::middleware('role:ads')->group(function () {
        Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
        Route::post('/ads', [AdController::class, 'store'])->name('ads.store');
        Route::put('/ads/{ad}', [AdController::class, 'update'])->name('ads.update');
        Route::post('/ads/{ad}/toggle', [AdController::class, 'toggle'])->name('ads.toggle');
        Route::delete('/ads/{ad}', [AdController::class, 'destroy'])->name('ads.destroy');
        Route::put('/ads-config', [AdController::class, 'updateConfig'])->name('ads.config');
    });

    // Comments: view, post a reply, delete. No approve/reject — see CommentController.
    Route::middleware('role:moderate')->group(function () {
        Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
        Route::post('/comments', [AdminCommentController::class, 'store'])->name('comments.store');
        Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
    });

    Route::middleware('role:members')->group(function () {
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::post('/members', [MemberController::class, 'store'])->name('members.store');
        Route::post('/members/{member}/toggle', [MemberController::class, 'toggle'])->name('members.toggle');
        Route::post('/members/{member}/reset-password', [MemberController::class, 'resetPassword'])->name('members.reset');
        Route::put('/meetings/{meeting}/access', [MemberController::class, 'meetingAccess'])->name('meetings.access');
    });

    Route::middleware('role:notify')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
        Route::post('/notifications/topics', [NotificationController::class, 'storeTopic'])->name('notifications.topics.store');
    });

    Route::middleware('role:analytics')->get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Roles & settings: Super Admin only, enforced by the 'super_admin' middleware alias.
    Route::middleware('super_admin')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::put('/roles', [RoleController::class, 'update'])->name('roles.update');
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
