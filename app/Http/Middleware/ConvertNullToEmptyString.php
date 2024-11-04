<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class ConvertNullToEmptyString
{
    public function handle($request, Closure $next)
    {
        // Chuyển đổi các trường null thành chuỗi rỗng
        $request->merge(array_map(function ($value) {
            return $value === null ? '' : $value;
        }, $request->all()));

        return $next($request);
    }
}