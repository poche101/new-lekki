<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Writes one row per public GET request. This is the entire "analytics"
 * pipeline for the prototype — real rows, but only as real as the traffic
 * hitting this app. See admin/analytics for what reads it back out.
 */
class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('get') && ! $request->ajax()) {
            $ua = (string) $request->userAgent();
            $device = preg_match('/Mobi|Android/i', $ua) ? 'mobile' : (preg_match('/Tablet|iPad/i', $ua) ? 'tablet' : 'desktop');

            PageView::create([
                'path' => $request->path(),
                'user_id' => $request->user()?->id,
                'session_id' => substr((string) $request->session()->getId(), 0, 64),
                'device_type' => $device,
                'referrer' => $request->headers->get('referer'),
                'viewed_at' => now(),
            ]);
        }

        return $response;
    }
}
