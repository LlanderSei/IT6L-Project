<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
  public function RegisterUser(Request $request) {
    // Check if the request is AJAX
    $isAjax = $request->expectsJson();

    $request->validateWithBag('register', [
      'Name' => 'required|regex:/^[A-Za-z]+(?:\s+[A-Za-z]+(?:\.[A-Za-z]*)?)?(?:\s+[A-Za-z]+(?:\.[A-Za-z]*)?){0,4}?$/m',
      'Username' => 'required|unique:users|regex:/^[A-Za-z0-9]+(_[A-Za-z0-9]+)?(\.[A-Za-z0-9]+)?$/',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6|confirmed',
      'password_confirmation' => 'required|min:6',
    ], [
      'Name.required' => 'The name field is required.',
      'Name.regex' => 'The name must be a valid name format, with optional Surname or Middle Initial.',
      'Username.required' => 'The username field is required.',
      'Username.unique' => 'This username is already taken.',
      'Username.regex' => 'The username must contain only letters, numbers, one optional underscore, and one optional dot (e.g., "john_doe.123"). No spaces or other special characters are allowed.',
      'email.required' => 'The email field is required.',
      'email.email' => 'The email must be a valid email address.',
      'email.unique' => 'This email is already registered.',
      'password.required' => 'The password field is required.',
      'password.min' => 'The password must be at least 6 characters.',
      'password.confirmed' => 'The password confirmation does not match.',
      'password_confirmation.required' => 'The password confirmation field is required.',
      'password_confirmation.min' => 'The password confirmation must be at least 6 characters.',
    ]);

    $user = User::create([
      'Name' => ucwords($request->Name),
      'Username' => $request->Username,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    // Automatically log in the user after registration
    Auth::login($user);

    if ($isAjax) {
      return response()->json([
        'status' => 'success',
        'message' => 'Registration successful! You are now logged in.',
        'user' => [
          'Name' => $user->Name,
          'email' => $user->email,
          'Role' => $user->Role,
        ],
      ]);
    }

    return back()->with('toast_success', 'Registration successful! You are now logged in.');
  }

  public function LoginUser(Request $request) {
    // Check if the request is AJAX
    $isAjax = $request->expectsJson();

    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
      if ($isAjax) {
        return response()->json([
          'status' => 'error',
          'message' => 'Login failed. Check your credentials.',
          'errors' => [
            'email' => ['Invalid credentials'],
          ],
        ], 422);
      }
      return back()->with([
        'toast_error' => 'Login failed. Check your credentials.',
        'LoginError' => 'Login failed. Check your credentials.'
      ])->withInput($request->only('email'));
    }

    $user = Auth::user();
    if ($isAjax) {
      return response()->json([
        'status' => 'success',
        'message' => 'Login successful!',
        'user' => [
          'Name' => $user->Name,
          'email' => $user->email,
          'Role' => $user->Role,
        ],
        'redirect' => $user->Role === 'Admin' ? route('admin.dashboard') : null,
      ]);
    }

    if ($user->Role === 'Admin') {
      return redirect()->route('admin.dashboard');
    }
    return back()->with('toast_success', 'Login successful!');
  }

  public function LogoutUser(Request $request) {
    // Check if the request is AJAX
    $isAjax = $request->expectsJson();

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if ($isAjax) {
      return response()->json([
        'status' => 'success',
        'message' => 'Logout successful!',
        'csrf_token' => csrf_token(),
      ]);
    }

    return redirect()->route('home')->with('toast_success', 'Logout successful!');
  }
}
