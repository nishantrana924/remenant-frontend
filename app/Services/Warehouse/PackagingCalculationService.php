<?php

namespace App\Services\Warehouse;

use App\Models\Order;
use Exception;

class PackagingCalculationService
{
    /**
     * Calculate volumetric data strictly using snapshot order items.
     * Throws exception or returns flag if live products are accessed or snapshots are missing.
     *
     * @param Order $order
     * @return array
     */
    public function calculateForOrder(Order $order): array
    {
        $totalWeight = 0;
        $maxLength = 0;
        $maxWidth = 0;
        $totalHeight = 0;
        $isMissingData = false;

        // Eager load order items to satisfy performance budget (No N+1)
        if (!$order->relationLoaded('orderItems')) {
            $order->load('orderItems');
        }

        foreach ($order->orderItems as $item) {
            // Strict Architecture Rule: Exclusively use snapshot data
            if (
                is_null($item->product_weight) || 
                is_null($item->product_length) || 
                is_null($item->product_width) || 
                is_null($item->product_height)
            ) {
                return [
                    'is_valid' => false,
                    'requires_manual_review' => true,
                    'reason' => 'Missing product snapshot dimensions for SKU: ' . ($item->product_sku ?? 'Unknown'),
                ];
            }

            $quantity = $item->quantity ?? 1;

            // Aggregate weights
            $totalWeight += ($item->product_weight * $quantity);
            
            // Volumetric approximation: Max base dims, sum heights.
            if ($item->product_length > $maxLength) {
                $maxLength = $item->product_length;
            }
            if ($item->product_width > $maxWidth) {
                $maxWidth = $item->product_width;
            }
            $totalHeight += ($item->product_height * $quantity);
        }

        return [
            'is_valid' => true,
            'requires_manual_review' => false,
            'reason' => null,
            'weight' => $totalWeight,
            'length' => $maxLength,
            'width' => $maxWidth,
            'height' => $totalHeight,
        ];
    }

}
