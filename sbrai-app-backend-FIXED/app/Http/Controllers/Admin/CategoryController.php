<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('listings')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('listing_type');

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:100|unique:categories,name',
            'listing_type' => 'required|in:product,service,property',
            'icon'         => 'nullable|string|max:10',
            'image'        => 'nullable|image|max:2048',
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        $data = [
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'listing_type' => $request->listing_type,
            'icon'         => $request->icon,
            'sort_order'   => $request->sort_order ?? 0,
            'is_active'    => true,
            'created_by'   => auth()->id(),
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    // 💡 Changed from (string $id) to (Category $category)
   public function edit(Category $category)
{
    // Laravel automatically looks up the model instance by its ID or UUID matching the URL parameter!
    return view('admin.categories.edit', compact('category'));
}

    // 💡 Changed from (string $id) to (Category $category)
    public function update(Request $request, Category $category)
{
    $request->validate([
        // 💡 Ignore the current record by matching its unique slug, bypassing corrupted IDs!
        'name'         => 'required|string|max:100|unique:categories,name,' . $category->slug . ',slug',
        'listing_type' => 'required|in:product,service,property',
        'icon'         => 'nullable|string|max:10',
        'image'        => 'nullable|image|max:2048',
        'sort_order'   => 'nullable|integer|min:0',
        'is_active'    => 'nullable|boolean',
    ]);

    $data = $request->only(['name', 'listing_type', 'icon', 'sort_order']);
    $data['slug']      = \Illuminate\Support\Str::slug($request->name);
    $data['is_active'] = $request->boolean('is_active');

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('categories', 'public');
        $data['image_url'] = '/storage/' . $path;
    }

    $category->update($data);

    return redirect()->route('admin.categories.index')
        ->with('success', 'Category updated successfully');
}
    // 💡 Changed from (string $id) to (Category $category)
    public function destroy(Category $category)
    {
        if ($category->listings()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete category with existing listings. Deactivate it instead.']);
        }

        $category->delete();
        return back()->with('success', 'Category deleted successfully');
    }

    // 💡 Changed from (string $id) to (Category $category)
    public function toggleActive(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        return back()->with('success', 'Category status updated');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }
}
