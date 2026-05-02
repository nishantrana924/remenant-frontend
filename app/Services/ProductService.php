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
            $data['image'] = \App\Helpers\ImageHelper::upload($data['image'], 'products');
        }

        if (isset($data['gallery'])) {
            $galleryPaths = [];
            foreach ($data['gallery'] as $file) {
                $galleryPaths[] = \App\Helpers\ImageHelper::upload($file, 'products/gallery');
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

        return $product;
    }

    public function update($id, array $data)
    {
        $product = $this->repository->find($id);

        // 1. Image & Gallery Update
        if (isset($data['image'])) {
            if ($product->image) \App\Helpers\ImageHelper::delete($product->image);
            $data['image'] = \App\Helpers\ImageHelper::upload($data['image'], 'products');
        }

        if (isset($data['gallery'])) {
            // Option: merge or replace. We'll replace for simplicity here.
            if ($product->gallery) {
                foreach($product->gallery as $old) \App\Helpers\ImageHelper::delete($old);
            }
            $galleryPaths = [];
            foreach ($data['gallery'] as $file) {
                $galleryPaths[] = \App\Helpers\ImageHelper::upload($file, 'products/gallery');
            }
            $data['gallery'] = $galleryPaths;
        }

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

        return $product;
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
