<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page; 
use Illuminate\Http\Request;
use App\Http\Resources\PageResource;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)
                    ->where('is_published', true)
                    ->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found.'], 404);
        }

        return new PageResource($page);
    }
}