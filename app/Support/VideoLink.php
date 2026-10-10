<?php

namespace App\Support;

/**
 * Turns a link pasted into the admin (YouTube, Vimeo, .mp4/.webm, .m3u8) into
 * something the frontend can play. Returns null for anything unsupported, which
 * is also how the admin forms validate the link.
 *
 * kind: 'iframe' (YouTube/Vimeo embed), 'hls' (.m3u8 stream) or 'file' (.mp4 etc.)
 */
class VideoLink
{
    public static function parse(?string $url): ?array
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        if (preg_match('~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:[^#]*&)?v=|embed/|live/|shorts/|v/))([\w-]{11})~i', $url, $m)) {
            return [
                'kind' => 'iframe', 'provider' => 'youtube', 'id' => $m[1],
                'src' => "https://www.youtube-nocookie.com/embed/{$m[1]}?rel=0&playsinline=1",
                'thumb' => "https://img.youtube.com/vi/{$m[1]}/hqdefault.jpg",
            ];
        }

        if (preg_match('~vimeo\.com/(?:video/|channels/[\w-]+/|groups/[\w-]+/videos/)?(\d+)~i', $url, $m)) {
            return ['kind' => 'iframe', 'provider' => 'vimeo', 'id' => $m[1], 'src' => "https://player.vimeo.com/video/{$m[1]}", 'thumb' => null];
        }

        $ext = strtolower(pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        if ($ext === 'm3u8') {
            return ['kind' => 'hls', 'provider' => 'hls', 'id' => null, 'src' => $url, 'thumb' => null];
        }
        if (in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'm4v'], true)) {
            return ['kind' => 'file', 'provider' => 'file', 'id' => null, 'src' => $url, 'thumb' => null];
        }

        return null;
    }

    public static function supported(?string $url): bool
    {
        return self::parse($url) !== null;
    }
}
