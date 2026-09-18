<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menuItems')->orderBy('sort_order')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form', [
            'category' => new Category(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $category = new Category();
        $this->fillFromRequest($category, $request, $data);
        $category->sort_order = Category::max('sort_order') + 1;
        $category->save();

        return redirect()->route('admin.categories.index')->with('status', "Category \"{$category->label}\" created.");
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request, $category->id);
        $this->fillFromRequest($category, $request, $data);
        $category->save();

        return redirect()->route('admin.categories.index')->with('status', "Category \"{$category->label}\" updated.");
    }

    public function destroy(Category $category)
    {
        if ($category->menuItems()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', "Can't delete \"{$category->label}\" — it still has menu items. Move or delete them first.");
        }

        if ($category->image_path && str_starts_with($category->image_path, 'uploads/')) {
            Storage::disk('public')->delete($category->image_path);
        }

        $label = $category->label;
        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', "Category \"{$label}\" deleted.");
    }

    public function moveUp(Category $category)
    {
        $prev = Category::where('sort_order', '<', $category->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($prev) {
            [$category->sort_order, $prev->sort_order] = [$prev->sort_order, $category->sort_order];
            $category->save();
            $prev->save();
        }

        return redirect()->route('admin.categories.index');
    }

    public function moveDown(Category $category)
    {
        $next = Category::where('sort_order', '>', $category->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            [$category->sort_order, $next->sort_order] = [$next->sort_order, $category->sort_order];
            $category->save();
            $next->save();
        }

        return redirect()->route('admin.categories.index');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'key' => [
                'required', 'string', 'max:50', 'alpha_dash',
                Rule::unique('categories', 'key')->ignore($ignoreId),
            ],
            'label' => ['required', 'string', 'max:100'],
            'emoji' => ['nullable', 'string', 'max:10'],
            'title' => ['required', 'string', 'max:150'],
            'title_ar' => ['nullable', 'string', 'max:150'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
        ]);
    }

    private function fillFromRequest(Category $category, Request $request, array $data): void
    {
        $category->fill([
            'key' => $data['key'],
            'label' => $data['label'],
            'emoji' => $data['emoji'] ?? null,
            'title' => $data['title'],
            'title_ar' => $data['title_ar'] ?? null,
        ]);

        if ($request->boolean('remove_image')) {
            if ($category->image_path && str_starts_with($category->image_path, 'uploads/')) {
                Storage::disk('public')->delete($category->image_path);
            }
            $category->image_path = null;
        }

        if ($request->hasFile('image')) {
            if ($category->image_path && str_starts_with($category->image_path, 'uploads/')) {
                Storage::disk('public')->delete($category->image_path);
            }
            $category->image_path = $request->file('image')->store('uploads/categories', 'public');
        }
    }
}
