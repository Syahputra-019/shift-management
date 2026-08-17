<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ShiftSwapRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employees = User::orderBy('name')->paginate(10);
        $pendingSwapCount = ShiftSwapRequest::where('status', 'pending')->count();

        return view('employees.index', compact('employees', 'pendingSwapCount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $employee
     * @return \Illuminate\View\View
     */
    public function edit(User $employee)
    {
        $pendingSwapCount = ShiftSwapRequest::where('status', 'pending')->count();
        return view('employees.edit', compact('employee', 'pendingSwapCount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $employee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($employee->id)],
            'department' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $employee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $employee)
    {
        // Add a check to prevent deleting the logged-in user
        if ($employee->id === auth()->id()) {
            return redirect()->route('employees.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
