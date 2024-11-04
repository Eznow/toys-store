<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // Show the registration form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle user registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'role' => 'customer', // Mặc định là 'customer'
        ]);
    
        // Login ngay sau khi đăng ký
        Auth::login($user);
    
        return redirect()->route('home');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        // Sử dụng Auth::attempt để xác thực người dùng
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        // Đăng nhập thành công, tạo session và redirect về home
        return redirect()->route('home');
    } else {
        // Đăng nhập thất bại, quay lại trang login
        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->withInput();
    }
    }

    // Show user profile
    public function profile()
    {
        return view('users.profile', ['user' => Auth::user()]);
    }

    public function edit()
{
    // Trả về view chỉnh sửa thông tin cá nhân
    $user = Auth::user();
    return view('user.edit', compact('user'));
}

public function update(Request $request)
{
    $user = Auth::user();

    // Validate thông tin người dùng
    $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'required|string|min:10',
        'address' => 'nullable|string|max:255',
    ]);

    // Cập nhật thông tin
    $user->update($request->only('name', 'phone_number', 'address'));

    return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
}

public function logout()
{
    Auth::logout();
    return redirect('/login');
}

// Hiển thị danh sách người dùng
public function index()
{
    $users = User::all(); // Lấy danh sách tất cả người dùng
    return view('admin.users.index', compact('users'));
}

// Thay đổi vai trò người dùng
public function changeRole(Request $request, User $user)
{
    $this->validate($request, [
        'role' => 'required|in:admin,seller,customer', // Các vai trò hợp lệ
    ]);

    // Thay đổi vai trò của người dùng
    $user->role = $request->role;
    $user->save();

    return redirect()->route('admin.users.index')->with('success', 'Vai trò của người dùng đã được thay đổi thành công.');
}
}
