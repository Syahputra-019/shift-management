<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employees = User::orderBy('name')->get();
        return view('employees.index', compact('employees'));
    }
}