<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


class PageController extends Controller
{
    /**
     * Renderiza a página de boas-vindas
     */
    public function welcome()
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => app()->version(),
            'phpVersion' => PHP_VERSION,
        ]);
    }

    /**
     * Renderiza o dashboard
     */
    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }
    
    /**
     * Renderiza a página de perfil
     */
    public function profile()
    {
        return Inertia::render('Profile/Show');
    }

     /**
     * Renderiza a página de exemplos de input
     */
    public function example()
    {
        return Inertia::render('Example');
    }
}