<?php
use App\Models\Product;

$p = Product::where('slug', 'pricing-architecture')->first();
if ($p) {
    $benefits = $p->benefits;
    if (isset($benefits[2])) {
        $benefits[2]['icon'] = 'star';
        $p->benefits = $benefits;
        $p->save();
        echo "Successfully updated icon for benefit 03\n";
    } else {
        echo "Benefit 03 not found\n";
    }
} else {
    echo "Product not found\n";
}
