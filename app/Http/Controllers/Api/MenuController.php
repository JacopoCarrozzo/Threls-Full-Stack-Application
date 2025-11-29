<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['links.page'])
            ->get();

        return response()->json([
            'data' => $menus,
            'status' => 'success'
        ]);
    }
}