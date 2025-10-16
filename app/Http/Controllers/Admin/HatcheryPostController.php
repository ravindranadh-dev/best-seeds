<?php

namespace App\Http\Controllers\Admin;

use App\Models\HatcheryPost;
use App\Http\Controllers\Controller;
use App\Models\HatcheryLocation;
use App\Models\Hatchery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HatcheryPostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = HatcheryPost::latest()->get();
        $hatcheries=Hatchery::all();
        return view('admin.hatchery-updates.index', compact('posts','hatcheries'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $hatcheries=Hatchery::all();
        return view('admin.hatchery-updates.create',compact('hatcheries'));
    }

    /**
     * Store a newly created post in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'media_path' => 'nullable|string|max:255',
    //         'media_type' => 'required|in:image,video',
    //         'hashtags' => 'nullable|string',
    //         'is_active' => 'required|boolean',
    //     ]);

    //     HatcheryPost::create([
    //         'title' => $request->title,
    //         'description' => $request->description,
    //         'media_path' => $request->media_path,
    //         'media_type' => $request->media_type,
    //         'hashtags' => $request->hashtags,
    //         'is_active' => $request->is_active,
    //     ]);

    //     return redirect()->route('hatchery-updates.index')->with('success', 'Post created successfully.');
    // }

    public function store(Request $request)
{
    // dd($request->all());
    $request->validate([
        'hatchery_id' => 'required|exists:hatcheries,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'media_type' => 'required|in:image,video',
        'media_path' => 'nullable|file',
        'hashtags' => 'nullable|string',
        'is_active' => 'required|boolean',
    ]);

    $mediaPath = null;

    if ($request->hasFile('media_path')) {
        $file = $request->file('media_path');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;

        $folder = $request->media_type === 'video' ? 'uploads/updates/' : 'uploads/updates/';

        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0777, true);
        }

        $file->move(public_path($folder), $filename);

        $mediaPath = $folder . $filename;
    }
    HatcheryPost::create([
        'hatchery_id' => $request->hatchery_id,
        'title' => $request->title,
        'description' => $request->description,
        'media_path' => $mediaPath,
        'media_type' => $request->media_type,
        'hashtags' => $request->hashtags,
        'is_active' => $request->is_active,
    ]);

    return redirect()
        ->route('hatchery-updates.index')
        ->with('success', 'Post created successfully.');
}


    /**
     * Show the form for editing the specified post.
     */
    public function edit($id)
    {
        $post = HatcheryPost::findOrFail($id);
        $hatcheries=Hatchery::all();
        return view('admin.hatchery-updates.edit', compact('post','hatcheries'));
    }

    /**
     * Update the specified post in storage.
     */
public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'media_type' => 'required|in:image,video',
        'media_path' => 'nullable',
        'hashtags' => 'nullable|string',
        'is_active' => 'required|boolean',
        'hatchery_id' => 'required|exists:hatcheries,id',
    ]);

    $post = HatcheryPost::findOrFail($id);
    $mediaPath = $post->media_path;
    if ($request->hasFile('media_path')) {
        $file = $request->file('media_path');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;

        $folder = $request->media_type === 'video' ? 'uploads/videos/' : 'uploads/images/';


        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0777, true);
        }
        if ($mediaPath && file_exists(public_path($mediaPath))) {
            unlink(public_path($mediaPath));
        }
        $file->move(public_path($folder), $filename);
        $mediaPath = $folder . $filename;
    }


    $post->update([
        'hatchery_id' => $request->hatchery_id,
        'title' => $request->title,
        'description' => $request->description,
        'media_path' => $mediaPath,
        'media_type' => $request->media_type,
        'hashtags' => $request->hashtags,
        'is_active' => $request->is_active,
    ]);

    return redirect()
        ->route('hatchery-updates.index')
        ->with('success', 'Post updated successfully.');
}


    /**
     * Remove the specified post from storage.
     */
    public function destroy($id)
    {
        $post = HatcheryPost::findOrFail($id);
        $post->delete();

        return redirect()->route('hatchery-updates.index')->with('success', 'Post deleted successfully.');
    }
}
