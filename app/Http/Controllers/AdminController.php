<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return response()->json(Admin::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $admin = Admin::create($validated);

        return response()->json($admin, 201);
    }

    public function show($id)
    {
        return response()->json(Admin::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->update($request->all());
        return response()->json($admin);
    }

    public function destroy($id)
    {
        Admin::destroy($id);
        return response()->json(['message' => 'Admin deleted successfully']);
    }
    
}
