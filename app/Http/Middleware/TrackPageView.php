<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && $response->isSuccessful() && ! $request->is('admin*', 'up', 'storage/*')) {
            PageView::create([
                'path' => '/'.ltrim($request->path(), '/'),
                'session_id' => $request->hasSession() ? $request->session()->getId() : '',
                'platform' => $request->userAgent() && preg_match('/Mobile|Android|iPhone|iPad/i', $request->userAgent()) ? 'mobile' : 'desktop',
                'created_at' => now(),
            ]);
        }

        return $response;
    }
}
