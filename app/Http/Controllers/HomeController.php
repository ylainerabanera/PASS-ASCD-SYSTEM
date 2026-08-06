<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {
        $this->middleware(['auth', 'admin'])->only('index');
    }

    /**
     * Show the application landing page for guests.
     */
    public function welcome()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // return view('welcome');
        return view('auth/login');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        return view('home', $this->dashboardService->getViewData());
    }
}
