<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index_sales()
    {
        return view('admin.contacts.sales.index');
    }

    public function add_sales()
    {
        return view('admin.contacts.add');
    }

    public function edit_sales()
    {
        return view('admin.contacts.edit');
    }
    public function index_tech()
    {
        return view('admin.contacts.tech.index');
    }

    public function add_tech()
    {
        return view('admin.contacts.add');
    }

    public function edit_tech()
    {
        return view('admin.contacts.edit');
    }
}
