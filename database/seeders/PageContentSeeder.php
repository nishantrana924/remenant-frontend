<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    public function run()
    {
        $aboutData = [
            'hero' => [
                'tag' => 'Our Philosophy',
                'title' => 'Where Science <br class="hidden lg:block"> Meets Engineering',
                'description' => 'REMENANT is where medical science meets advanced engineering. We set out to engineer a gold standard in daily nutrition by combining clinical medical expertise with precision manufacturing to master the art of bioavailability.',
                'image' => 'images/about/remenant-bg.jpg'
            ],
            'features' => [
                [
                    'icon' => 'zap',
                    'title' => 'Effervescent Tech',
                    'description' => 'By focusing on Effervescent Technology, we ensure maximum bioavailability and faster absorption for every single nutrient.',
                    'bg_class' => 'bg-[var(--bg-sage)]',
                    'text_color' => '#074D3D'
                ],
                [
                    'icon' => 'shield-check',
                    'title' => 'Pure & Potent',
                    'description' => 'Our chemical engineering expertise allows us to master the stability and quality of tablets, ensuring every sip is precise.',
                    'bg_class' => 'bg-[var(--bg-peach)]',
                    'text_color' => '#6B2C24'
                ],
                [
                    'icon' => 'microscope',
                    'title' => 'Effortless Health',
                    'description' => 'We believe that staying healthy should be effortless, scientific, and transparent. No more boring pills.',
                    'bg_class' => 'bg-[var(--primary-soft)]',
                    'text_color' => '#84310B'
                ]
            ],
            'vision' => [
                'tag' => 'Our Vision',
                'title' => 'Revolutionizing <br> Modern Longevity',
                'description' => 'To be the global benchmark for bioavailable nutrition, ensuring that every individual has access to the most advanced, science-backed wellness solutions for a life without limits.',
                'image' => 'images/banners/remenant-bg13.jpg'
            ],
            'mission' => [
                'tag' => 'Our Mission',
                'title' => 'Engineering <br> Daily Excellence',
                'description' => 'We are on a mission to simplify health by combining medical precision with engineering brilliance. We create supplements that are not just effective, but a joy to consume every single day.',
                'image' => 'images/banners/remenant-mission.jpg'
            ],
            'process' => [
                'tag' => 'How It\'s Made',
                'title' => 'The Gold Standard Process',
                'steps' => [
                    [
                        'number' => '01',
                        'title' => 'Ethical Sourcing',
                        'description' => 'We only partner with suppliers who meet our rigorous purity standards. Every nutrient is verified for grade and potency before entering our clinical-standard facility.',
                        'image' => 'images/banners/remenant-bg5.jpg',
                        'bg_class' => 'bg-gradient-to-br from-white to-[#E8EEF2]',
                        'accent_color' => 'bg-black'
                    ],
                    [
                        'number' => '02',
                        'title' => 'Precision Engineering',
                        'description' => 'Our Chemical Engineers optimize tablet stability and solubility. Using Effervescent technology, we ensure nutrients are protected until they hit your glass.',
                        'image' => 'images/banners/remenant-bg2.jpg',
                        'bg_class' => 'bg-[#FFF1E8]',
                        'accent_color' => 'bg-[var(--primary)]'
                    ],
                    [
                        'number' => '03',
                        'title' => 'Double-Blind Testing',
                        'description' => 'Every batch undergoes third-party lab testing to guarantee potency and safety. If it doesn\'t meet the gold standard, it doesn\'t leave our doors.',
                        'image' => 'images/banners/remenant-bg12.jpg',
                        'bg_class' => 'bg-gradient-to-br from-white to-[#E6F3ED]',
                        'accent_color' => 'bg-[#074D3D]'
                    ]
                ]
            ],
            'founders' => [
                'tag' => 'The People Behind the Standard',
                'title' => 'Meet the Founders',
                'list' => [
                    [
                        'name' => 'Dr. Jimmy Thummar',
                        'role' => 'Founder & Visionary',
                        'degree' => 'MBBS',
                        'bio' => [
                            'With a solid foundation in medicine, Dr. Thummar is the driving force behind REMENANT. His medical expertise ensures that every product is designed to fulfill the body’s actual nutritional gaps.',
                            'As the visionary, he ensures that the brand stays committed to health safety and clinical efficacy, bridging the gap between clinical needs and daily lifestyle.'
                        ],
                        'image' => 'images/about/remenant-jimmy.jpg',
                        'reverse' => false
                    ],
                    [
                        'name' => 'Het Lakhani',
                        'role' => 'Co-Founder & Operations',
                        'degree' => 'Chemical Engineer',
                        'bio' => [
                            'Bringing technical brilliance to the brand, Het Lakhani oversees the complex formulation and manufacturing processes.',
                            'His background in chemical engineering allows REMENANT to master the stability and quality of effervescent tablets, ensuring that every batch is pure, potent, and precise.'
                        ],
                        'image' => 'images/about/remenant-het.jpg',
                        'reverse' => true
                    ]
                ]
            ],
            'certifications' => [
                'title' => 'Trust & Quality Guaranteed',
                'subtitle' => 'We adhere to the highest international standards to ensure every REMENANT product is pure and effective.',
                'list' => [
                    ['id' => 'iso', 'name' => 'ISO Certified', 'desc' => 'Quality Management'],
                    ['id' => 'haccp', 'name' => 'HACCP', 'desc' => 'Food Safety'],
                    ['id' => 'gmp', 'name' => 'GMP Consistent', 'desc' => 'Manufacturing Practice'],
                    ['id' => 'fda', 'name' => 'FDA Registered', 'desc' => 'Facility Standard'],
                    ['id' => 'kosher', 'name' => 'Kosher', 'desc' => 'Certified Quality'],
                ]
            ],
            'cta' => [
                'title' => 'Ready to Upgrade Your Health?',
                'description' => 'Experience the ultimate in bioavailability and convenience. Our science-backed formulations are designed to seamlessly integrate into your modern lifestyle.',
                'image' => 'images/about/remenant-cta.png',
                'whatsapp' => 'https://wa.me/17567776796',
                'shop_url' => '/#shop'
            ]
        ];

        // --- Legal Pages Data ---
        $privacyData = [
            'seo' => ['title' => 'Privacy Policy - Remenant Health', 'description' => 'Learn how we handle your personal data and protect your privacy.'],
            'title' => 'Privacy Policy',
            'last_updated' => 'May 03, 2026',
            'sections' => [
                ['title' => 'Data Collection', 'content' => 'We collect information you provide directly to us when you create an account...'],
                ['title' => 'How We Use Data', 'content' => 'We use the information we collect to provide, maintain, and improve our services...'],
            ]
        ];

        $termsData = [
            'seo' => ['title' => 'Terms & Conditions - Remenant Health', 'description' => 'Read our terms of service and legal agreements.'],
            'title' => 'Terms & Conditions',
            'last_updated' => 'May 03, 2026',
            'sections' => [
                ['title' => 'Introduction', 'content' => 'Welcome to Remenant Health. These Terms and Conditions govern your use of our website...'],
                ['title' => 'Eligibility', 'content' => 'By using our services, you represent and warrant that you are at least 18 years of age...'],
            ]
        ];

        $shippingData = [
            'seo' => ['title' => 'Shipping Guide - Remenant Health', 'description' => 'Information about our shipping rates, delivery times, and policies.'],
            'title' => 'Shipping Guide',
            'last_updated' => 'May 03, 2026',
            'sections' => [
                ['title' => 'Delivery Times', 'content' => 'Standard shipping usually takes 3-5 business days...'],
            ]
        ];

        $refundData = [
            'seo' => ['title' => 'Refund Policy - Remenant Health', 'description' => 'Our policy on returns, refunds, and exchanges.'],
            'title' => 'Refund Policy',
            'last_updated' => 'May 03, 2026',
            'sections' => [
                ['title' => 'Returns', 'content' => 'If you are not satisfied with your purchase, you can return it within 30 days...'],
            ]
        ];

        // --- Save All Pages ---
        $pages = [
            'about' => $aboutData,
            'privacy-policy' => $privacyData,
            'terms-and-conditions' => $termsData,
            'shipping-guide' => $shippingData,
            'refund-policy' => $refundData,
        ];

        foreach ($pages as $slug => $data) {
            PageContent::updateOrCreate(
                ['slug' => $slug],
                ['content' => $data, 'status' => 'published']
            );
        }
    }
}
