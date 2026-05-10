<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::updateOrCreate(
            ['slug' => 'vitamin-c-effervescent'],
            [
                'title' => 'Vitamin C Effervescent',
                'tagline' => 'Immunity & Skin Health',
                'price' => 1799,
                'mrp' => 2499,
                'description' => 'A refreshing citrus burst designed to boost your immunity.',
                'long_description' => '<p>Our Vitamin C Effervescent tablets are the perfect daily companion for your immune system.</p>',
                'image' => 'products/vitamin-c.jpg',
                'gallery' => ['products/vitamin-c.jpg'],
                'rating' => 4.8,
                'reviews' => 1243,
                'reviews_count' => 1243,
                'theme_color' => 'orange',
                'status' => 'published',
                'benefits' => [
                    ['icon' => 'shield', 'title' => 'Immunity Boost', 'desc' => 'Strengthens defenses.'],
                    ['icon' => 'sparkles', 'title' => 'Skin Glow', 'desc' => 'Supports collagen.'],
                    ['icon' => 'zap', 'title' => 'Energy Boost', 'desc' => 'Keep moving all day.'],
                    ['icon' => 'droplets', 'title' => 'Hydration', 'desc' => 'Perfect with 200ml water.'],
                ],
                'highlights' => [
                    '100% Bioavailable Effervescent Formula',
                    'Clean Label Certified Ingredients',
                    'Zero Sugar & No Artificial Colors',
                    'Fast Acting & Gentle on the Stomach'
                ],
                'ritual' => [
                    ['step' => 1, 'title' => 'Drop it', 'desc' => 'Drop one tablet into 200ml of water.'],
                    ['step' => 2, 'title' => 'Fizz it', 'desc' => 'Watch the pure wellness dissolve.'],
                    ['step' => 3, 'title' => 'Fuel Up', 'desc' => 'Drink and take on your day.'],
                ],
                'specs' => [
                    'Flavor' => 'Orange Burst',
                    'Quantity' => '20 Tablets',
                    'Form' => 'Effervescent Tablets',
                    'Main Ingredient' => 'Vitamin C (1000mg)'
                ]
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'glutathione-skin-glow'],
            [
                'title' => 'Glutathione Effervescent',
                'tagline' => 'Skin Glow & Detox',
                'price' => 1999,
                'mrp' => 2999,
                'description' => 'Radiant skin starts from within with our premium L-Glutathione formula.',
                'long_description' => '<p>Our L-Glutathione formula is designed for maximum absorption and clinical results.</p>',
                'image' => 'products/glutathione.jpg',
                'gallery' => ['products/glutathione.jpg'],
                'rating' => 5.0,
                'reviews' => 842,
                'reviews_count' => 842,
                'theme_color' => 'pink',
                'status' => 'published',
                'benefits' => [
                    ['icon' => 'sparkles', 'title' => 'Skin Radiance', 'desc' => 'Reduces melanin production.'],
                    ['icon' => 'sun', 'title' => 'UV Protection', 'desc' => 'Internal sun protection.'],
                    ['icon' => 'refresh-cw', 'title' => 'Detoxification', 'desc' => 'Flushes out toxins.'],
                    ['icon' => 'heart', 'title' => 'Anti-Aging', 'desc' => 'Fights oxidative stress.'],
                ],
                'highlights' => [
                    '500mg L-Glutathione (Reduced Form)',
                    'Enhanced with Vitamin C & Vitamin E',
                    'Clinically Proven Skin Brightening',
                    'Refreshing Strawberry Flavor'
                ],
                'ritual' => [
                    ['step' => 1, 'title' => 'The Setup', 'desc' => 'Prepare 250ml of chilled water.'],
                    ['step' => 2, 'title' => 'The Fizz', 'desc' => 'Add one strawberry tablet.'],
                    ['step' => 3, 'title' => 'The Glow', 'desc' => 'Sip and let your skin thank you.'],
                ],
                'specs' => [
                    'Flavor' => 'Strawberry Splash',
                    'Quantity' => '15 Tablets',
                    'Form' => 'Effervescent',
                    'Main Ingredient' => 'L-Glutathione (500mg)'
                ]
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'daily-multivitamin-plus'],
            [
                'title' => 'Daily Multivitamin Plus',
                'tagline' => 'Complete Nutrition & Vitality',
                'price' => 1499,
                'mrp' => 1999,
                'description' => '24 essential vitamins and minerals in one daily fizz.',
                'long_description' => '<p>Get your daily dose of essential nutrients with our scientifically balanced multivitamin.</p>',
                'image' => 'products/multivitamin.jpg',
                'gallery' => ['products/multivitamin.jpg'],
                'rating' => 4.9,
                'reviews' => 2105,
                'reviews_count' => 2105,
                'theme_color' => 'blue',
                'status' => 'published',
                'benefits' => [
                    ['icon' => 'activity', 'title' => 'Daily Vitality', 'desc' => 'Keeps you active.'],
                    ['icon' => 'brain', 'title' => 'Mental Focus', 'desc' => 'Supports cognitive function.'],
                    ['icon' => 'bone', 'title' => 'Bone Health', 'desc' => 'Calcium & Vitamin D3.'],
                    ['icon' => 'eye', 'title' => 'Vision Support', 'desc' => 'Vitamin A & Lutein.'],
                ],
                'highlights' => [
                    'Full Spectrum Vitamin B-Complex',
                    'Added Probiotics for Gut Health',
                    'Natural Fruit Extracts',
                    'Sugar-Free Daily Nutrition'
                ],
                'ritual' => [
                    ['step' => 1, 'title' => 'Morning Start', 'desc' => 'Drop one tablet in a glass of water.'],
                    ['step' => 2, 'title' => 'Wait for it', 'desc' => 'Let it dissolve completely.'],
                    ['step' => 3, 'title' => 'Go Ahead', 'desc' => 'Drink with your breakfast.'],
                ],
                'specs' => [
                    'Flavor' => 'Mixed Berry',
                    'Quantity' => '30 Tablets',
                    'Form' => 'Effervescent',
                    'Main Ingredient' => '24 Essential Minerals'
                ]
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'apple-cider-vinegar-effervescent'],
            [
                'title' => 'ACV Effervescent',
                'tagline' => 'Digestion & Weight Control',
                'price' => 1299,
                'mrp' => 1799,
                'description' => 'All the benefits of ACV with "the mother", without the harsh taste.',
                'long_description' => '<p>Our Apple Cider Vinegar tablets offer a delicious way to support your weight goals and digestion.</p>',
                'image' => 'products/acv.jpg',
                'gallery' => ['products/acv.jpg'],
                'rating' => 4.7,
                'reviews' => 3421,
                'reviews_count' => 3421,
                'theme_color' => 'red',
                'status' => 'published',
                'benefits' => [
                    ['icon' => 'wind', 'title' => 'Digestion', 'desc' => 'Reduces bloating.'],
                    ['icon' => 'scale', 'title' => 'Weight Loss', 'desc' => 'Supports metabolism.'],
                    ['icon' => 'droplet', 'title' => 'Detox', 'desc' => 'Natural gut cleanse.'],
                    ['icon' => 'smile', 'title' => 'Taste', 'desc' => 'Delicious green apple flavor.'],
                ],
                'highlights' => [
                    'Organic ACV with the Mother',
                    'Added Vitamin B12 & B6',
                    'Enriched with Pomegranate & Beetroot',
                    'Gentle on Tooth Enamel'
                ],
                'ritual' => [
                    ['step' => 1, 'title' => 'Prepare', 'desc' => 'Take 200ml of water.'],
                    ['step' => 2, 'title' => 'Dissolve', 'desc' => 'Drop one green apple tablet.'],
                    ['step' => 3, 'title' => 'Enjoy', 'desc' => 'Drink 20 mins before your meal.'],
                ],
                'specs' => [
                    'Flavor' => 'Green Apple',
                    'Quantity' => '15 Tablets',
                    'Form' => 'Effervescent',
                    'Main Ingredient' => 'Organic ACV (500mg)'
                ]
            ]
        );
    }
}
