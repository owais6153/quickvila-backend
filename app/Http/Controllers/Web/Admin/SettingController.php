<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Requests\Admin\SettingRequest;

class SettingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-setting', ['index']);
        $this->middleware('permission:edit-setting', ['store']);
        $this->dir = 'admin.setting.index';
    }

    public function index($key)
    {
        if (view()->exists("admin.setting.type.$key" ))
        {
            $setting = Setting::where('key', $key)->first();

            $setting = isset($setting->value) ? unserialize($setting->value) : false;

            return view($this->dir, compact('key', 'setting'));
        }
        abort(404);
    }
    public function store(SettingRequest $request)
    {
        $setting = Setting::where('key', $request->key)->first();
        if(empty($setting)){
            $setting = Setting::create([
                'key' => $request->key,
                'value' => serialize($request->setting)
            ]);
            return redirect()->back()->with('success', 'Setting Inserted');
        }
        else{
            $setting->update([
                'value' => serialize($request->setting)
            ]);
            return redirect()->back()->with('success', 'Setting Updated');
        }
    }
}
