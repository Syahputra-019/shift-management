<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display the reports page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // For now, just return the view.
        // Logic for fetching report data can be added here later.
        return view('reports.index');
    }
}