<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.client.index');
    }

    public function add()
    {
        return view('admin.users.client.add');
    }

    public function edit()
    {
        return view('admin.users.client.edit');
    }


    public function indexAdmin()
    {
        return view('admin.users.admin.index');
    }

    public function addAdmin()
    {
        return view('admin.users.admin.add');
    }

    public function editAdmin()
    {
        return view('admin.users.admin.edit');
    }
}
