<?php

use App\Models\Project;
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
