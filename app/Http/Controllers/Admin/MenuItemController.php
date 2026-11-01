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
        $query = MenuItem::query()->with('category');

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('description_ar', 'like', "%{$search}%");
            });
        }

        $categoryId = $request->query('category');
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $status = $request->query('status');
        if ($status === 'enabled') {
            $query->where('is_active', true);
        } elseif ($status === 'disabled') {
            $query->where('is_active', false);
        }

        // Reordering (sort_order) only means something within a single category, so
        // only sort by it — and only show the up/down controls — when a category
        // filter is active. Otherwise keep alphabetical for easier browsing/search.
        $reorderable = (bool) $categoryId;
        if ($reorderable) {
            $query->orderBy('sort_order')->orderBy('name');
        } else {
            $query->orderBy('name');
        }

        // Reordering swaps sort_order with a neighbor, which only makes sense if
        // the whole category's items are visible on one page — bump the page
        // size so a category never gets split across pages while reordering.
        $items = $query->paginate($reorderable ? 100 : 30)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.menu-items.index', compact('items', 'categories', 'reorderable'));
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
        if (empty($data['sort_order'])) {
            $data['sort_order'] = MenuItem::where('category_id', $data['category_id'])->max('sort_order') + 1;
        }

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

    public function moveUp(MenuItem $menuItem)
    {
        $prev = MenuItem::where('category_id', $menuItem->category_id)
            ->where('sort_order', '<', $menuItem->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($prev) {
            [$menuItem->sort_order, $prev->sort_order] = [$prev->sort_order, $menuItem->sort_order];
            $menuItem->save();
            $prev->save();
        }

        return back();
    }

    public function moveDown(MenuItem $menuItem)
    {
        $next = MenuItem::where('category_id', $menuItem->category_id)
            ->where('sort_order', '>', $menuItem->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            [$menuItem->sort_order, $next->sort_order] = [$next->sort_order, $menuItem->sort_order];
            $menuItem->save();
            $next->save();
        }

        return back();
    }

    public function toggleActive(MenuItem $menuItem)
    {
        $menuItem->update(['is_active' => ! $menuItem->is_active]);

        $status = $menuItem->is_active ? 'enabled' : 'disabled';

        return back()->with('status', "Item \"{$menuItem->name}\" {$status}.");
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
            'description_ar' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
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
            'description_ar' => $data['description_ar'] ?? null,
            'sort_order' => $data['sort_order'] ?? $item->sort_order ?? 0,
            'is_active' => $request->boolean('is_active'),
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
