<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
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
        $data['sort_order'] = Category::max('sort_order') + 1;

        $category = Category::create($data);

        return redirect()->route('admin.categories.index')->with('status', "Category \"{$category->label}\" created.");
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->validated($request, $category->id));

        return redirect()->route('admin.categories.index')->with('status', "Category \"{$category->label}\" updated.");
    }

    public function destroy(Category $category)
    {
        if ($category->menuItems()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', "Can't delete \"{$category->label}\" — it still has menu items. Move or delete them first.");
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
        ]);
    }
}
