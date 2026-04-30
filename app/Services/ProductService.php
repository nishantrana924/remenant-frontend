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

        $data['slug'] = \Illuminate\Support\Str::slug($data['title']);

        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->repository->find($id);

        if (isset($data['image'])) {
            \App\Helpers\ImageHelper::delete($product->image);
            $data['image'] = \App\Helpers\ImageHelper::upload($data['image'], 'products');
        }

        if (isset($data['gallery'])) {
            // Logic to handle gallery update (can be extended to delete old ones)
            $galleryPaths = [];
            foreach ($data['gallery'] as $file) {
                $galleryPaths[] = \App\Helpers\ImageHelper::upload($file, 'products/gallery');
            }
            $data['gallery'] = $galleryPaths;
        }

        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
