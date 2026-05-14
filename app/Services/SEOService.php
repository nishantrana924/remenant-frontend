<?php

namespace App\Services;

use Illuminate\Support\Str;

class SEOService
{
    protected $tags = [];
    protected $schema = [];
    protected $canonical;

    public function __construct()
    {
        $this->tags = [
            'title' => config('app.name', 'Remenant Health'),
            'description' => 'Premium Healthcare & Wellness eCommerce platform. Quality products for your health journey.',
            'keywords' => 'healthcare, wellness, medicine, health products, Remenant Health',
            'og_title' => config('app.name', 'Remenant Health'),
            'og_description' => 'Premium Healthcare & Wellness eCommerce platform.',
            'og_image' => asset('images/og-default.jpg'),
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image',
            'robots' => 'index, follow',
        ];
        
        $this->canonical = request()->url();

        // Default Organization Schema
        $this->addSchema('Organization', [
            'name' => config('app.name'),
            'url' => config('app.url'),
            'logo' => asset('images/logo/remenant-health-logo.png'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+91-7567776796',
                'contactType' => 'customer service',
                'email' => 'support@remenant.in',
                'areaServed' => 'IN',
                'availableLanguage' => ['en', 'hi']
            ],
            'sameAs' => [
                'https://www.facebook.com/share/14e2f2h6obs/?mibextid=wwXIfr',
                'https://www.instagram.com/remenant_health'
            ]
        ]);

        // Default LocalBusiness Schema
        $this->addSchema('LocalBusiness', [
            'name' => config('app.name'),
            'image' => asset('images/logo/remenant-health-logo.png'),
            '@id' => config('app.url'),
            'url' => config('app.url'),
            'telephone' => '+91-7567776796',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => '224, Ambika Pinnacle Mall, Lajamani Chowk, Mota Varachha',
                'addressLocality' => 'Surat',
                'addressRegion' => 'Gujarat',
                'postalCode' => '394101',
                'addressCountry' => 'IN'
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 21.2386,
                'longitude' => 72.8893
            ],
            'openingHoursSpecification' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'opens' => '09:00',
                'closes' => '20:00'
            ]
        ]);
    }

    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
            return $this;
        }

        if ($key === 'title') {
            $this->tags['title'] = $value;
            $this->tags['og_title'] = $value;
        } elseif ($key === 'description') {
            $this->tags['description'] = Str::limit($value, 160);
            $this->tags['og_description'] = Str::limit($value, 160);
        } elseif ($key === 'image') {
            $this->tags['og_image'] = $value;
        } else {
            $this->tags[$key] = $value;
        }

        return $this;
    }

    public function setCanonical($url)
    {
        $this->canonical = $url;
        return $this;
    }

    public function addSchema($type, array $data)
    {
        $this->schema[$type] = array_merge([
            '@context' => 'https://schema.org',
            '@type' => $type,
        ], $data);
        return $this;
    }

    public function render()
    {
        $html = [];

        // Meta Tags
        $html[] = '<title>' . e($this->tags['title']) . '</title>';
        $html[] = '<meta name="description" content="' . e($this->tags['description']) . '">';
        $html[] = '<meta name="keywords" content="' . e($this->tags['keywords']) . '">';
        $html[] = '<meta name="robots" content="' . e($this->tags['robots']) . '">';
        $html[] = '<link rel="canonical" href="' . e($this->canonical) . '">';

        // Open Graph
        $html[] = '<!-- Open Graph -->';
        $html[] = '<meta property="og:site_name" content="' . e(config('app.name')) . '">';
        $html[] = '<meta property="og:title" content="' . e($this->tags['og_title']) . '">';
        $html[] = '<meta property="og:description" content="' . e($this->tags['og_description']) . '">';
        $html[] = '<meta property="og:image" content="' . e($this->tags['og_image']) . '">';
        $html[] = '<meta property="og:url" content="' . e($this->canonical) . '">';
        $html[] = '<meta property="og:type" content="' . e($this->tags['og_type']) . '">';

        // Twitter Card
        $html[] = '<!-- Twitter Card -->';
        $html[] = '<meta name="twitter:card" content="' . e($this->tags['twitter_card']) . '">';
        $html[] = '<meta name="twitter:title" content="' . e($this->tags['og_title']) . '">';
        $html[] = '<meta name="twitter:description" content="' . e($this->tags['og_description']) . '">';
        $html[] = '<meta name="twitter:image" content="' . e($this->tags['og_image']) . '">';

        // JSON-LD Schema
        if (!empty($this->schema)) {
            $html[] = '<!-- Structured Data -->';
            foreach ($this->schema as $s) {
                $html[] = '<script type="application/ld+json">' . json_encode($s, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
            }
        }

        return implode("\n    ", $html);
    }
}
