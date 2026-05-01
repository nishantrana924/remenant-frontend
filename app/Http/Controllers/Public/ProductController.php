<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function getProducts()
    {
        return [
            [
                'id' => 1,
                'slug' => 'vitamin-c-effervescent',
                'title' => 'Remenant Vitamin C Effervescent',
                'tagline' => 'Immunity & Skin Health',
                'description' => 'A refreshing citrus burst designed to boost your immunity and support radiant skin health. Packed with 1000mg of Vitamin C, this formula helps in collagen production and provides powerful antioxidant protection for a healthy, glowing you.',
                'long_description' => 'Our Vitamin C Effervescent tablets are the perfect daily companion for your immune system. Unlike traditional pills, these effervescent tablets dissolve quickly in water, creating a delicious fizzy drink that is easy on the stomach and absorbed faster by the body. Each tablet is formulated with premium quality L-Ascorbic Acid and Zinc to provide a double layer of protection against seasonal ailments while promoting natural skin radiance.',
                'price' => 1799,
                'mrp' => 2499,
                'rating' => 4.8,
                'reviews' => 1243,
                'image' => 'remenant-product11.jpg',
                'gallery' => ['remenant-product11.jpg', 'remenant-product12.jpg', 'remenant-product13.jpg'],
                'color_theme' => 'orange',
                'color' => 'from-orange-500 to-amber-500',
                'benefits' => [
                    ['title' => 'Immunity Boost', 'desc' => 'Strengthens your natural defenses.', 'icon' => 'shield'],
                    ['title' => 'Skin Glow', 'desc' => 'Supports natural collagen production.', 'icon' => 'sparkles'],
                    ['title' => 'Antioxidant', 'desc' => 'Fights free radicals and oxidative stress.', 'icon' => 'zap'],
                    ['title' => 'Daily Energy', 'desc' => 'Keeps you active throughout the day.', 'icon' => 'battery-charging'],
                ],
                'specs' => [
                    'Flavor' => 'Orange Burst',
                    'Quantity' => '20 Tablets',
                    'Dosage' => '1 Tablet Daily',
                    'Shelf Life' => '18 Months'
                ]
            ],
            [
                'id' => 2,
                'slug' => 'biotin-effervescent',
                'title' => 'Remenant Biotin Effervescent',
                'tagline' => 'Beauty & Strength',
                'description' => 'Targeted formula for hair strength, nail health, and glowing skin from within. Infused with Sesbania Grandiflora extract and Zinc for maximum beauty benefits.',
                'long_description' => 'Achieve your beauty goals with our premium Biotin Effervescent tablets. Formulated with a blend of Biotin, Vitamin E, and Zinc, these tablets support hair follicle health, prevent nail brittleness, and maintain skin elasticity. The delicious green apple flavor makes it a treat to consume, ensuring you never miss your daily beauty ritual.',
                'price' => 1699,
                'mrp' => 2399,
                'rating' => 4.7,
                'reviews' => 982,
                'image' => 'remenant-product12.jpg',
                'gallery' => ['remenant-product12.jpg', 'remenant-product10.jpg', 'remenant-product13.jpg'],
                'color_theme' => 'emerald',
                'color' => 'from-emerald-500 to-lime-500',
                'benefits' => [
                    ['title' => 'Hair Growth', 'desc' => 'Supports keratin production for stronger hair.', 'icon' => 'user'],
                    ['title' => 'Nail Strength', 'desc' => 'Reduces brittleness and promotes growth.', 'icon' => 'check-circle'],
                    ['title' => 'Skin Radiance', 'desc' => 'Improves texture and natural glow.', 'icon' => 'sun'],
                    ['title' => 'Metabolism', 'desc' => 'Helps in energy production from nutrients.', 'icon' => 'activity'],
                ],
                'specs' => [
                    'Flavor' => 'Green Apple',
                    'Quantity' => '20 Tablets',
                    'Dosage' => '1 Tablet Daily',
                    'Shelf Life' => '18 Months'
                ]
            ],
            [
                'id' => 3,
                'slug' => 'acv-effervescent',
                'title' => 'Remenant ACV Effervescent',
                'tagline' => 'Metabolism & Digestion',
                'description' => 'A metabolism booster and digestive aid without the harsh taste of liquid vinegar. Made with real Himalayan apples and infused with Pomegranate extract.',
                'long_description' => 'Say goodbye to the pungent taste of liquid Apple Cider Vinegar. Our effervescent tablets give you all the benefits of ACV with "Mother" in a refreshing, easy-to-drink format. It helps in weight management, improves digestion, and supports detoxification. Each tablet is equivalent to a shot of liquid ACV but much kinder to your tooth enamel and throat.',
                'price' => 1599,
                'mrp' => 2199,
                'rating' => 4.6,
                'reviews' => 761,
                'image' => 'remenant-product13.jpg',
                'gallery' => ['remenant-product13.jpg', 'remenant-product11.jpg', 'remenant-product12.jpg'],
                'color_theme' => 'green',
                'color' => 'from-green-500 to-emerald-500',
                'benefits' => [
                    ['title' => 'Weight Care', 'desc' => 'Helps in healthy weight management.', 'icon' => 'scale'],
                    ['title' => 'Digestive Aid', 'desc' => 'Reduces bloating and improves gut health.', 'icon' => 'heart'],
                    ['title' => 'Detox Support', 'desc' => 'Flushes out toxins from the body.', 'icon' => 'refresh-ccw'],
                    ['title' => 'Sugar Balance', 'desc' => 'Supports healthy blood sugar levels.', 'icon' => 'droplet'],
                ],
                'specs' => [
                    'Flavor' => 'Green Apple & Pomegranate',
                    'Quantity' => '20 Tablets',
                    'Dosage' => '1 Tablet before meals',
                    'Shelf Life' => '18 Months'
                ]
            ],
            [
                'id' => 4,
                'slug' => 'glutathione-effervescent',
                'title' => 'Remenant Glutathione Effervescent',
                'tagline' => 'Master Antioxidant',
                'description' => 'The ultimate formula for skin brightening, detoxification, and overall cellular wellness. Contains 500mg L-Glutathione and Vitamin C.',
                'long_description' => 'Unlock your inner radiance with our Master Antioxidant formula. Glutathione is known as the body\'s primary detoxifier, helping to clear toxins and heavy metals while significantly brightening the skin tone by inhibiting melanin production. Combined with Vitamin C, it works synergistically for anti-aging and skin health. This is our most premium formula for those seeking a total skin transformation.',
                'price' => 1999,
                'mrp' => 2999,
                'rating' => 4.9,
                'reviews' => 604,
                'image' => 'remenant-product10.jpg',
                'gallery' => ['remenant-product10.jpg', 'remenant-product11.jpg', 'remenant-product12.jpg'],
                'color_theme' => 'rose',
                'color' => 'from-red-500 to-rose-500',
                'benefits' => [
                    ['title' => 'Skin Brightening', 'desc' => 'Promotes an even and radiant skin tone.', 'icon' => 'award'],
                    ['title' => 'Cellular Repair', 'desc' => 'Protects cells from oxidative damage.', 'icon' => 'shield-plus'],
                    ['title' => 'Anti-Aging', 'desc' => 'Reduces fine lines and improves elasticity.', 'icon' => 'clock'],
                    ['title' => 'Locker Detox', 'desc' => 'Supports liver health and detoxification.', 'icon' => 'filter'],
                ],
                'specs' => [
                    'Flavor' => 'Strawberry Bliss',
                    'Quantity' => '20 Tablets',
                    'Dosage' => '1 Tablet Daily on empty stomach',
                    'Shelf Life' => '18 Months'
                ]
            ],
        ];
    }

    public function index()
    {
        $products = $this->getProducts();
        return view('public.products.index', compact('products'));
    }

    public function show($slug)
    {
        $allProducts = $this->getProducts();
        $product = collect($allProducts)->firstWhere('slug', $slug);

        if (!$product) {
            abort(404);
        }

        $relatedProducts = collect($allProducts)->where('slug', '!=', $slug)->take(4);

        return view('public.products.show', compact('product', 'relatedProducts'));
    }

    public function reviews($slug)
    {
        $allProducts = $this->getProducts();
        $product = collect($allProducts)->firstWhere('slug', $slug);

        if (!$product) {
            abort(404);
        }

        return view('public.products.reviews', compact('product'));
    }
}
