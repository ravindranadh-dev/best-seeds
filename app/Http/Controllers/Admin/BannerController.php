<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Banner;

use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    // 📥 Index: List banners with optional screen filter
    public function index(Request $request)
    {
        $query = Banner::query();

        if ($request->filled('screen')) {
            $query->where('screen', $request->screen);
        }

        $banners = $query->orderByDesc('id')->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    // 🆕 Create: Show form
    public function create()
    {
        return view('admin.banners.create');
    }

    // 💾 Store: Save new banner
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'nullable|string|max:255',
            'image'         => 'nullable|string|max:255',
            'redirect_url'  => 'nullable|url|max:255',
            'status'        => 'required|boolean',
            'screen'        => 'nullable|string|max:11',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Banner::create($validator->validated());

        return redirect()->route('banners.index')->with('success', 'Banner created successfully.');
    }

    // 🛠 Edit: Show edit form
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    // 🔁 Update: Save changes
    public function update(Request $request, Banner $banner)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'nullable|string|max:255',
            'image'         => 'nullable|string|max:255',
            'redirect_url'  => 'nullable|url|max:255',
            'status'        => 'required|boolean',
            'screen'        => 'nullable|string|max:11',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $banner->update($validator->validated());

        return redirect()->route('banners.index')->with('success', 'Banner updated successfully.');
    }

    // ❌ Destroy: Delete banner
    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Banner deleted successfully.');
    }
}

