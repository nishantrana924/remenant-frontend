<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('variants')->latest()->paginate(20);
        
        // Stats for the top bar (all low stock items across entire DB)
        $low_stock_items = [];
        $all_low = Product::with('variants')->get();
        foreach ($all_low as $p) {
            if ($p->variants->count() > 0) {
                foreach ($p->variants as $v) {
                    if ($v->stock <= 10) {
                        $low_stock_items[] = [
                            'name' => $p->title . ' (' . ($v->size ?? $v->color ?? $v->weight) . ')',
                            'stock' => $v->stock,
                        ];
                    }
                }
            } else {
                if ($p->stock <= 10) {
                    $low_stock_items[] = [
                        'name' => $p->title,
                        'stock' => $p->stock,
                    ];
                }
            }
        }

        return view('admin.inventory.index', compact('products', 'low_stock_items'));
    }

    public function logs()
    {
        $logs = \App\Models\InventoryLog::with(['product', 'user'])->latest()->paginate(50);
        return view('admin.inventory.logs', compact('logs'));
    }

    public function updateStock(Request $request)
    {
        if ($request->type == 'product') {
            $product = Product::findOrFail($request->id);
            $oldStock = $product->stock;
            $product->update(['stock' => $request->stock]);
            
            \App\Models\InventoryLog::create([
                'product_id' => $product->id,
                'old_stock' => $oldStock,
                'new_stock' => $request->stock,
                'change_amount' => $request->stock - $oldStock,
                'reason' => 'manual_update',
                'user_id' => auth()->id()
            ]);
        } else {
            $variant = ProductVariant::findOrFail($request->id);
            $variant->update(['stock' => $request->stock]);
            // For now, variants might not have detailed logs in the same table or need separate handling
        }

        return response()->json(['message' => 'Stock updated successfully']);
    }
}
