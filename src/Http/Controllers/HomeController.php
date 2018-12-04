<?php

namespace Poowf\Otter\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Single page application catch-all route.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('otter::pages.index');
    }
}