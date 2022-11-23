<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;

class AttributeOptionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-attribute', ['index', 'getList']);
        $this->middleware('permission:create-attribute', ['create', 'store']);
        $this->middleware('permission:edit-attribute', ['edit', 'update']);
        $this->middleware('permission:delete-attribute', ['destroy']);
        $this->dir = 'admin.attribute.options.';
    }
    public function index()
    {
        # code...
    }

    public function create(Attribute $attribute)
    {
        # code...
    }
}
