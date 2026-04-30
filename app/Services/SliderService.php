<?php

namespace App\Services;

use App\Repositories\SliderRepositoryInterface;

class SliderService
{
    protected $repository;

    public function __construct(SliderRepositoryInterface $repository)
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
        if (isset($data['image_desktop'])) {
            $data['image_desktop'] = \App\Helpers\ImageHelper::upload($data['image_desktop'], 'sliders');
        }
        if (isset($data['image_mobile'])) {
            $data['image_mobile'] = \App\Helpers\ImageHelper::upload($data['image_mobile'], 'sliders');
        }
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        $slider = $this->repository->find($id);

        if (isset($data['image_desktop'])) {
            \App\Helpers\ImageHelper::delete($slider->image_desktop);
            $data['image_desktop'] = \App\Helpers\ImageHelper::upload($data['image_desktop'], 'sliders');
        }
        if (isset($data['image_mobile'])) {
            \App\Helpers\ImageHelper::delete($slider->image_mobile);
            $data['image_mobile'] = \App\Helpers\ImageHelper::upload($data['image_mobile'], 'sliders');
        }

        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
