<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $about = PageContent::where('slug', 'about')->first();
        
        if (!$about) {
            // Fallback if seeder hasn't run
            return view('public.about', ['data' => $this->getDefaultData()]);
        }

        return view('public.about', ['data' => $about->content]);
    }

    /**
     * Preview unsaved content
     */
    public function preview(Request $request)
    {
        $data = $request->input('content');
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        
        // Merge with defaults to prevent missing key errors in view
        $data = array_replace_recursive($this->getDefaultData(), (array)$data);
        
        return view('public.about', ['data' => $data, 'isPreview' => true]);
    }

    private function getDefaultData()
    {
        return [
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
                'whatsapp' => 'https://wa.me/+917567776796?text=Hello%20Remenant%20Health%2C%20I%20have%20a%20question%20about%20your%20products.',
                'shop_url' => '/#shop'
            ]
        ];
    }
}
