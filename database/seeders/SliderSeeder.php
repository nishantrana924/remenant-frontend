<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            [
                'image_desktop' => 'remenant-bg22.jpg',
                'image_mobile' => 'remenant-bg1.jpg',
                'alt_text' => 'Banner 1',
                'order' => 1,
            ],
            [
                'image_desktop' => 'remenant-bg20.jpg',
                'image_mobile' => 'remenant-bg2.jpg',
                'alt_text' => 'Banner 2',
                'order' => 2,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
