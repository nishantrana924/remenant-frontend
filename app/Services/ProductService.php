<?php

namespace App\Services;

use App\Repositories\ProductRepositoryInterface;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        // 1. Image & Gallery Processing
        if (isset($data['image'])) {
            $data['image'] = \App\Helpers\ImageHelper::upload($data['image'], 'uploads/products');
        }

        if (isset($data['gallery'])) {
            $galleryPaths = [];
            foreach ($data['gallery'] as $file) {
                $galleryPaths[] = \App\Helpers\ImageHelper::upload($file, 'uploads/products/gallery');
            }
            $data['gallery'] = $galleryPaths;
        }

        // 2. SEO & Slug
        $data['slug'] = $data['slug'] ?? \Illuminate\Support\Str::slug($data['title']);
        $data['is_featured'] = isset($data['is_featured']) ? (bool)$data['is_featured'] : false;

        // 3. Extract Relationships
        $categories = $data['categories'] ?? [];
        $variants = $data['variants'] ?? [];
        unset($data['categories'], $data['variants']);

        // 4. Create Product
        $product = $this->repository->create($data);

        // 5. Handle Variants
        if (!empty($variants)) {
            foreach ($variants as $variant) {
                $product->variants()->create($variant);
            }
        }

        // 6. Handle Categories
        if (!empty($categories)) {
            $product->categories()->sync($categories);
        }

        // 7. Log initial stock
        if ($product->stock > 0) {
            \App\Models\InventoryLog::create([
                'product_id' => $product->id,
                'old_stock' => 0,
                'new_stock' => $product->stock,
                'change_amount' => $product->stock,
                'reason' => 'initial_creation',
                'user_id' => auth()->id()
            ]);
        }

        return $product;
    }

    public function update($id, array $data)
    {
        $product = $this->repository->find($id);
        $oldStock = $product->stock;

        // 1. Image & Gallery Update
        if (isset($data['image'])) {
            if ($product->image) \App\Helpers\ImageHelper::delete($product->image);
            $data['image'] = \App\Helpers\ImageHelper::upload($data['image'], 'uploads/products');
        }

        // Handle deleted gallery images
        $currentGallery = $product->gallery ?? [];
        if (isset($data['deleted_gallery'])) {
            foreach ($data['deleted_gallery'] as $path) {
                \App\Helpers\ImageHelper::delete($path);
                $currentGallery = array_filter($currentGallery, fn($p) => $p !== $path);
            }
        }

        // Handle new gallery uploads
        if (isset($data['gallery'])) {
            $newGalleryPaths = [];
            foreach ($data['gallery'] as $file) {
                $newGalleryPaths[] = \App\Helpers\ImageHelper::upload($file, 'uploads/products/gallery');
            }
            $currentGallery = array_merge($currentGallery, $newGalleryPaths);
        }
        
        $data['gallery'] = array_values($currentGallery);
        unset($data['deleted_gallery']);

        // 2. Transformations
        $data['is_featured'] = isset($data['is_featured']) ? (bool)$data['is_featured'] : false;

        // 3. Extract Relationships
        $categories = $data['categories'] ?? null;
        $variants = $data['variants'] ?? null;
        unset($data['categories'], $data['variants']);

        // 4. Update Product
        $product = $this->repository->update($id, $data);

        // 5. Update Variants
        if (is_array($variants)) {
            $product->variants()->delete();
            foreach ($variants as $variant) {
                $product->variants()->create($variant);
            }
        }

        // 6. Update Categories
        if (is_array($categories)) {
            $product->categories()->sync($categories);
        }

        // 7. Log stock change if updated
        if (isset($data['stock']) && (int)$data['stock'] !== $oldStock) {
            \App\Models\InventoryLog::create([
                'product_id' => $product->id,
                'old_stock' => $oldStock,
                'new_stock' => $product->stock,
                'change_amount' => $product->stock - $oldStock,
                'reason' => 'manual_update',
                'user_id' => auth()->id()
            ]);
        }

        return $product;
    }

    public function delete($id)
    {
        $product = $this->repository->find($id);
        if ($product) {
            // Delete Main Image
            if ($product->image) {
                \App\Helpers\ImageHelper::delete($product->image);
            }

            // Delete Gallery Images
            if ($product->gallery && is_array($product->gallery)) {
                foreach ($product->gallery as $image) {
                    \App\Helpers\ImageHelper::delete($image);
                }
            }

            return $this->repository->delete($id);
        }
        return false;
    }

    public function bulkDelete(array $ids)
    {
        foreach ($ids as $id) {
            $this->delete($id);
        }
        return true;
    }
}
