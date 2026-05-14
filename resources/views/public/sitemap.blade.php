<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Static Pages --}}
    @foreach($staticPages as $url)
    <url>
        <loc>{{ $url }}</loc>
        <lastmod>{{ now()->startOfMonth()->format('Y-m-d') }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>{{ str_contains($url, 'products') ? '0.9' : ($url == route('home') ? '1.0' : '0.7') }}</priority>
    </url>
    @endforeach

    {{-- Product Categories --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ route('products.index', ['categories' => [$category->slug]]) }}</loc>
        <lastmod>{{ $category->updated_at->format('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- Individual Products --}}
    @foreach($products as $product)
    <url>
        <loc>{{ route('products.show', $product->slug) }}</loc>
        <lastmod>{{ $product->updated_at->format('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
</urlset>
