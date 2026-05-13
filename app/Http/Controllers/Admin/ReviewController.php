<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $items = Review::with(['product', 'user'])->latest()->paginate(15);
        return view('admin.reviews.index', compact('items'));
    }

    public function updateStatus(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->update([
            'status' => $request->input('status')
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review status updated to ' . $request->input('status')
            ]);
        }

        return redirect()->back()->with('success', 'Review updated.');
    }

    public function toggleFeatured($id)
    {
        $review = Review::findOrFail($id);
        $review->update([
            'is_featured' => !$review->is_featured
        ]);

        return response()->json([
            'success' => true,
            'message' => $review->is_featured ? 'Review featured' : 'Review unfeatured'
        ]);
    }

    public function updateField(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $field = $request->input('field');
        $value = $request->input('value');
        
        if (in_array($field, ['location', 'comment', 'rating'])) {
            $review->update([$field => $value]);
            return response()->json(['success' => true, 'message' => ucfirst($field) . ' updated']);
        }
        
        return response()->json(['success' => false, 'message' => 'Invalid field'], 422);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully.'
            ]);
        }

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
    }

    public function bulkAction(Request $request)
    {
        $ids = $request->input('ids', []);
        $action = $request->input('action');

        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No reviews selected']);

        if ($action === 'approve') {
            Review::whereIn('id', $ids)->update(['status' => 'approved']);
        } elseif ($action === 'reject') {
            Review::whereIn('id', $ids)->update(['status' => 'rejected']);
        } elseif ($action === 'delete') {
            Review::whereIn('id', $ids)->delete();
        }

        return response()->json(['success' => true, 'message' => 'Bulk action completed']);
    }
}
