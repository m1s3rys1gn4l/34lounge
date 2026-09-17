<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $query = MenuItem::query()->with('category')->orderBy('name');

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->query('category')) {
            $query->where('category_id', $categoryId);
        }

        $items = $query->paginate(30)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.menu-items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.menu-items.form', [
            'item' => new MenuItem(),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $item = new MenuItem();
        $this->fillFromRequest($item, $request, $data);
        $item->save();

        return redirect()->route('admin.menu-items.index')->with('status', "Item \"{$item->name}\" created.");
    }

    public function edit(MenuItem $menuItem)
    {
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.menu-items.form', [
            'item' => $menuItem,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $data = $this->validated($request, $menuItem->id);
        $this->fillFromRequest($menuItem, $request, $data);
        $menuItem->save();

        return redirect()->route('admin.menu-items.index')->with('status', "Item \"{$menuItem->name}\" updated.");
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image_path && str_starts_with($menuItem->image_path, 'uploads/')) {
            Storage::disk('public')->delete($menuItem->image_path);
        }

        $name = $menuItem->name;
        $menuItem->delete();

        return redirect()->route('admin.menu-items.index')->with('status', "Item \"{$name}\" deleted.");
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'item_key' => [
                'required', 'string', 'max:50',
                Rule::unique('menu_items', 'item_key')->ignore($ignoreId),
            ],
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'badge' => ['nullable', 'string', 'max:50'],
            'emoji' => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }

    private function fillFromRequest(MenuItem $item, Request $request, array $data): void
    {
        $item->fill([
            'category_id' => $data['category_id'],
            'item_key' => $data['item_key'],
            'name' => $data['name'],
            'name_ar' => $data['name_ar'] ?? null,
            'price' => $data['price'],
            'badge' => $data['badge'] ?? null,
            'emoji' => $data['emoji'] ?? null,
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? $item->sort_order ?? 0,
        ]);

        if ($request->boolean('remove_image')) {
            if ($item->image_path && str_starts_with($item->image_path, 'uploads/')) {
                Storage::disk('public')->delete($item->image_path);
            }
            $item->image_path = null;
        }

        if ($request->hasFile('image')) {
            if ($item->image_path && str_starts_with($item->image_path, 'uploads/')) {
                Storage::disk('public')->delete($item->image_path);
            }
            $item->image_path = $request->file('image')->store('uploads', 'public');
        }
    }
}
