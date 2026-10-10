<?php

namespace App\Http\Controllers;

class StaticPageController extends Controller
{
    public function show(string $slug)
    {
        abort_unless(in_array($slug, ['privacy', 'terms', 'cookies', 'about', 'leadership'], true), 404);

        return view("pages.$slug");
    }
}
