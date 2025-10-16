<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HatcheryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HatcheryCategoryController extends Controller
{

    public function index()
    {
        $data = HatcheryCategory::orderBy('priority', 'asc')->get();

        return view('admin.hatchery-categories.index', compact('data'));
    }


    public function create()
    {
        return view('admin.hatchery-categories.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'priority'      => 'nullable|integer|min:0',
        ]);

        try {
            HatcheryCategory::create([
                'category_name' => $request->category_name,
                'priority'      => $request->priority ?? 0,

            ]);

            return redirect()->route('hatchery-categories.index')
                ->with('success', 'Hatchery category created successfully.');

        } catch (\Exception $e) {
            // dd($e);
            Log::error('Error creating hatchery category: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create category.');
        }
    }

    public function edit($id)
    {
        $data = HatcheryCategory::findOrFail($id);
        return view('admin.hatchery-categories.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'priority'      => 'nullable|integer|min:0',
        ]);

        try {
            $category = HatcheryCategory::findOrFail($id);
            $category->update([
                'category_name' => $request->category_name,
                'priority'      => $request->priority ?? 0,
            ]);

            return redirect()->route('hatchery-categories.index')
                ->with('success', 'Category updated successfully.');

        } catch (\Exception $e) {
            Log::error('Error updating hatchery category: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update category.');
        }
    }


    public function destroy($id)
    {
        try {
            $category = HatcheryCategory::findOrFail($id);
            $category->delete();

            return redirect()->route('hatchery-categories.index')
                ->with('success', 'Category deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error deleting hatchery category: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete category.');
        }
    }
}
