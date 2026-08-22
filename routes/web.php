<?php

use App\Models\Project;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('home', [
    'projects' => Project::published()->get(),
]))->name('home');

Route::get('/tentang-kami', fn () => Inertia::render('about'))->name('about');

Route::get('/testimoni', fn () => Inertia::render('testimonials', [
    'testimonials' => Testimonial::with('project:id,name')
        ->where('is_published', true)->orderBy('sort')->get(),
]))->name('testimonials');

Route::get('/perumahan/{project:slug}', fn (Project $project) => Inertia::render('project', [
    'project' => $project->load('houseTypes'),
]))->name('project');

/**
 * Sitemap untuk Google Search Console. Dibangun dari data CMS, jadi perumahan
 * baru langsung ikut terdaftar tanpa ada yang perlu diingat manual.
 */
Route::get('/sitemap.xml', function () {
    $projects = Project::published()->get(['slug', 'updated_at']);
    $site = Setting::current();

    $urls = collect([
        ['loc' => url('/'), 'lastmod' => $site->updated_at, 'changefreq' => 'weekly', 'priority' => '1.0'],
        ['loc' => url('/tentang-kami'), 'lastmod' => $site->updated_at, 'changefreq' => 'monthly', 'priority' => '0.7'],
        ['loc' => url('/testimoni'), 'lastmod' => $site->updated_at, 'changefreq' => 'monthly', 'priority' => '0.6'],
    ])->concat($projects->map(fn (Project $project) => [
        'loc' => url('/perumahan/'.$project->slug),
        'lastmod' => $project->updated_at,
        'changefreq' => 'weekly',
        'priority' => '0.9',
    ]));

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');
