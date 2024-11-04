<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng ký và Đăng nhập</title>
    <!-- Bootstrap 5.3.0 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            Trang Chủ
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
        <form id="header-search-form" action="{{ route('home') }}" method="GET" class="form-inline my-2 my-lg-0">
    <input type="text" id="header-search-input" name="query" class="form-control mr-sm-2" placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
</form>

            <ul class="navbar-nav mr-auto">
                <!-- Trang chủ -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>

                <!-- Giỏ hàng -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cart.view') }}">Giỏ hàng</a>
                </li>

                <!-- Lịch sử mua hàng (chỉ dành cho người đã đăng nhập) -->
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('orders.history') }}">Lịch sử mua hàng</a>
                </li>
                @endauth

                <!-- Khu vực quản trị (chỉ dành cho admin) -->
                @auth
                @if(Auth::user()->role == 'admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Khu vực quản trị
                    </a>
                    <div class="dropdown-menu" aria-labelledby="adminDropdown">
                        <a class="dropdown-item" href="{{ route('products.create') }}">Thêm sản phẩm</a>
                        <a class="dropdown-item" href="{{ route('categories.create') }}">Thêm danh mục sản phẩm</a>
                        <a class="dropdown-item" href="{{ route('orders.history') }}">Quản lý đơn hàng</a>
                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">Quản lý người dùng</a>
                        <a class="dropdown-item" href="{{ route('admin.complaints.index') }}">Quản lý Khiếu nại</a>
                        <a class="dropdown-item"  href="{{ route('discount-codes.index') }}">Quản lý Mã giảm giá</a>

                    </div>
                </li>
                @endif
                @endauth
            </ul>

            <!-- Phần chào tên người dùng và đăng nhập/đăng xuất -->
            <div class="ms-auto">
                @auth
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle text-primary" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Xin chào, {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('user.edit') }}">Chỉnh sửa thông tin cá nhân</a>
                            <a class="dropdown-item" href="{{ route('orders.history') }}">Xem lịch sử mua hàng</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                               Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập</a>
                @endauth
            </div>
        </div>
    </div>
</nav>




    <main class="py-4">
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
