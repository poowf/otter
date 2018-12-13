<?php

namespace Poowf\Otter\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Single page application catch-all route.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('otter::pages.dashboard');
    }
}