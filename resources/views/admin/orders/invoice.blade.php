@php
    $invoiceSettings = [
        'show_company' => \App\Models\SiteSetting::getValue('invoice_company_name_show', '1'),
        'company_name' => \App\Models\SiteSetting::getValue('invoice_company_name', 'REMENANT'),
        'prefix' => \App\Models\SiteSetting::getValue('invoice_prefix', 'REM'),
        'logo' => \App\Models\SiteSetting::getValue('invoice_logo'),
        'signature' => \App\Models\SiteSetting::getValue('invoice_signature'),
        'page_size' => \App\Models\SiteSetting::getValue('invoice_page_size', 'A4'),
        'custom_fields' => json_decode(\App\Models\SiteSetting::getValue('invoice_custom_fields', '[]'), true),
    ];
    $warehouse = \App\Models\Warehouse::where('is_default', true)->first() ?? \App\Models\Warehouse::first();
    
    // Warehouse details
    $wName = ($warehouse && $warehouse->name && $warehouse->name !== 'N/A') ? $warehouse->name : 'Tech-Connect Retail Private Limited';
    $wContact = ($warehouse && $warehouse->contact_person && $warehouse->contact_person !== 'N/A') ? $warehouse->contact_person : 'Warehouse Manager';
    $wPhone = ($warehouse && $warehouse->phone && $warehouse->phone !== '0000000000') ? $warehouse->phone : '7567776796';
    $wAddress = ($warehouse && $warehouse->address && $warehouse->address !== 'N/A') ? $warehouse->address : '224 Ambika Pinnacle Mall Lajamani Chowk Mota Varachha';
    $wAddress2 = ($warehouse && $warehouse->address_2 && $warehouse->address_2 !== 'N/A') ? $warehouse->address_2 : '';
    $wCity = ($warehouse && $warehouse->city && $warehouse->city !== 'N/A') ? $warehouse->city : 'Surat';
    $wState = ($warehouse && $warehouse->state && $warehouse->state !== 'N/A') ? $warehouse->state : 'Gujarat';
    $wPincode = ($warehouse && $warehouse->pincode && $warehouse->pincode !== '000000') ? $warehouse->pincode : '394101';
    $wGst = ($warehouse && $warehouse->gst_number && $warehouse->gst_number !== 'N/A' && $warehouse->gst_number !== '') ? $warehouse->gst_number : '24CBUPT5159C1Z8';
    
    // Address format
    $fullWarehouseAddress = trim($wAddress . ' ' . $wAddress2) . ', ' . $wCity . ', ' . $wState . ' - ' . $wPincode;
    
    // State tax determination
    $customerState = trim(strtolower($order->state));
    $warehouseStateLower = trim(strtolower($wState));
    $isIntraState = str_contains($customerState, $warehouseStateLower) || str_contains($warehouseStateLower, $customerState);
    
    // Platform fee details
    $hasPlatformFee = ($order->total_amount - ($order->shipping_charge ?? 0)) >= 10.00;
    $platformFee = $hasPlatformFee ? 7.00 : 0.00;
    
    $orderDate = $order->created_at->format('d-m-Y');
    
    // Items subtotal
    $itemsSubtotalOriginal = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);
    
    // Realistic Document Invoice numbers
    $prodInvoiceNo = 'FAFO7Z' . date('y', strtotime($order->created_at)) . str_pad($order->id, 8, '0', STR_PAD_LEFT);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice - #{{ $prodInvoiceNo }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; margin: 0 !important; background: white; color: black; }
            .invoice-page { 
                border: none !important; 
                box-shadow: none !important; 
                width: 100% !important; 
                margin: 0 !important; 
                padding: 4mm 6mm !important; 
                min-height: auto !important;
            }
            @page { 
                size: A4;
                margin: 6mm 8mm; 
            }
            .table-cell {
                padding: 3px 5px !important;
                font-size: 9px !important;
            }
            .block-margin {
                margin-bottom: 6px !important;
            }
            .block-padding {
                padding-bottom: 6px !important;
            }
            .title-section {
                margin-bottom: 8px !important;
            }
            .logo-img {
                height: 24px !important;
            }
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
        }
        .invoice-page {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 800px;
            padding: 24px;
            min-height: 980px;
        }
        .table-cell {
            padding: 6px 8px;
            font-size: 11px;
            line-height: 1.2;
        }
        .block-margin {
            margin-bottom: 12px;
        }
        .block-padding {
            padding-bottom: 12px;
        }
        .title-section {
            margin-bottom: 16px;
        }
        .logo-img {
            height: 32px;
        }
    </style>
</head>
<body class="text-slate-800 text-[11px]">

    <!-- Actions Panel -->
    <div class="no-print fixed top-6 right-6 flex gap-3 z-50">
        <button onclick="window.print()" class="bg-orange-600 text-white px-6 py-2 rounded-full font-bold shadow-lg hover:bg-orange-700 transition-all flex items-center gap-2 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Invoice
        </button>
        <button onclick="window.close()" class="bg-white text-slate-600 border border-slate-200 px-6 py-2 rounded-full font-bold shadow-sm hover:bg-slate-50 transition-all text-sm">Close</button>
    </div>

    <!-- ================= CONSOLIDATED SINGLE-PAGE INVOICE ================= -->
    <div class="invoice-page flex flex-col justify-between">
        <div>
            <!-- Header E. & O.E -->
            <div class="flex justify-between items-center border-b border-slate-200 pb-1 mb-2 text-[9px] text-slate-400 font-semibold uppercase tracking-wider">
                <span>E. & O.E.</span>
                <span>Page 1 of 1</span>
            </div>

            <!-- Title & Warranty Note -->
            <div class="flex justify-between items-start title-section">
                <div>
                    <h1 class="text-lg font-bold uppercase tracking-tight text-slate-900 leading-none">Tax Invoice</h1>
                    <p class="text-[8px] text-slate-500 font-bold mt-0.5 leading-tight">*Keep this invoice and manufacturer box for warranty purposes.</p>
                </div>
                <div class="text-right">
                    @if($invoiceSettings['logo'])
                        <img src="{{ Storage::url($invoiceSettings['logo']) }}" class="logo-img w-auto ml-auto mb-1">
                    @endif
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $invoiceSettings['company_name'] }}</p>
                </div>
            </div>

            <!-- Sold By & Addresses -->
            <div class="grid grid-cols-2 gap-4 text-[10px] block-margin border-b border-slate-100 block-padding">
                <div>
                    <p class="font-extrabold uppercase text-[8px] text-slate-400 tracking-wider mb-0.5">Sold By:</p>
                    <p class="font-bold text-slate-950 text-xs leading-none">{{ $wName }}</p>
                    <p class="text-slate-600 mt-0.5 leading-tight">{{ $fullWarehouseAddress }}</p>
                    <div class="mt-1 flex flex-wrap gap-x-3 gap-y-0.5 text-slate-500 text-[9px]">
                        <p><span class="font-bold text-slate-700">PAN:</span> AAICA4872D</p>
                        <p><span class="font-bold text-slate-700">CIN:</span> U52100HR2010PTC068415</p>
                        <p><span class="font-bold text-slate-700">GSTIN:</span> <span class="text-slate-950 font-bold">{{ $wGst }}</span></p>
                    </div>
                </div>
                <div class="bg-slate-50/50 p-2.5 rounded-lg border border-slate-100">
                    <div class="grid grid-cols-2 gap-x-2 gap-y-1.5 text-[9px]">
                        <div>
                            <p class="text-slate-400 font-bold uppercase text-[8px] tracking-wider leading-none">Invoice Number:</p>
                            <p class="font-bold text-slate-950 text-xs mt-0.5">#{{ $prodInvoiceNo }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 font-bold uppercase text-[8px] tracking-wider leading-none">Order ID:</p>
                            <p class="font-bold text-slate-950 text-xs mt-0.5">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 font-bold uppercase text-[8px] tracking-wider leading-none">Invoice Date:</p>
                            <p class="font-bold text-slate-800 mt-0.5">{{ $orderDate }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 font-bold uppercase text-[8px] tracking-wider leading-none">Order Date:</p>
                            <p class="font-bold text-slate-800 mt-0.5">{{ $orderDate }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ship To & Bill To -->
            <div class="grid grid-cols-2 gap-4 text-[10px] block-margin">
                <div class="border border-slate-100 p-2.5 rounded-lg">
                    <p class="font-extrabold text-slate-400 uppercase text-[8px] tracking-wider mb-1 leading-none">Ship To:</p>
                    <p class="font-bold text-slate-950 text-[11px] leading-tight">{{ $order->customer_name }}</p>
                    <p class="text-slate-600 mt-0.5 leading-tight">
                        {{ $order->address }}<br>
                        @if($order->landmark) {{ $order->landmark }}, @endif
                        {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}
                    </p>
                    <p class="font-bold text-slate-800 mt-1 text-[9px]">Phone: {{ $order->phone }}</p>
                </div>
                <div class="border border-slate-100 p-2.5 rounded-lg">
                    <p class="font-extrabold text-slate-400 uppercase text-[8px] tracking-wider mb-1 leading-none">Bill To:</p>
                    <p class="font-bold text-slate-950 text-[11px] leading-tight">{{ $order->customer_name }}</p>
                    <p class="text-slate-600 mt-0.5 leading-tight">
                        {{ $order->address }}<br>
                        @if($order->landmark) {{ $order->landmark }}, @endif
                        {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}
                    </p>
                    <p class="font-bold text-slate-800 mt-1 text-[9px]">Phone: {{ $order->phone }}</p>
                </div>
            </div>

            <!-- Product Items Table -->
            <table class="w-full text-left border-collapse border border-slate-200 block-margin">
                <thead>
                    <tr class="bg-slate-50 text-[8px] font-bold text-slate-500 uppercase border-b border-slate-200 tracking-wider">
                        <th class="table-cell border-r border-slate-200 w-[45%]">Product Details / Description</th>
                        <th class="table-cell border-r border-slate-200 text-center w-[8%]">Qty</th>
                        <th class="table-cell border-r border-slate-200 text-right w-[11%]">Gross Amt</th>
                        <th class="table-cell border-r border-slate-200 text-right w-[11%]">Discounts</th>
                        <th class="table-cell border-r border-slate-200 text-right w-[11%]">Taxable Val</th>
                        <th class="table-cell border-r border-slate-200 text-center w-[12%]">GST</th>
                        <th class="table-cell text-right w-[12%]">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @php
                        $grandGrossAmt = 0;
                        $grandDiscountAmt = 0;
                        $grandTaxableAmt = 0;
                        $grandTaxAmt = 0;
                        $grandInvoiceTotal = 0;
                    @endphp
                    
                    {{-- 1. Product Rows --}}
                    @foreach($order->orderItems as $item)
                        @php
                            $hsn = $item->product->hsn_code ?? '64041110';
                            $title = $item->product->title ?? $item->product->name ?? 'Product';
                            $gstRate = 18.0; 
                            if (str_contains(strtolower($title), 'shoes') || str_contains(strtolower($title), 'running') || str_starts_with($hsn, '64')) {
                                $gstRate = ($item->price < 1000) ? 5.0 : 12.0;
                            }
                            
                            $grossAmt = $item->price * $item->quantity;
                            
                            // Pro-rate discount + platform fee share
                            $proportion = ($itemsSubtotalOriginal > 0) ? ($grossAmt / $itemsSubtotalOriginal) : 0;
                            $itemCouponDiscount = $proportion * $order->discount_amount;
                            $itemPlatformFeeShare = $proportion * $platformFee;
                            $totalItemDiscount = $itemCouponDiscount + $itemPlatformFeeShare;
                            
                            $netItemTotal = $grossAmt - $totalItemDiscount;
                            $taxableVal = $netItemTotal / (1 + ($gstRate / 100));
                            $taxAmt = $netItemTotal - $taxableVal;
                            
                            // Accumulators
                            $grandGrossAmt += $grossAmt;
                            $grandDiscountAmt += $totalItemDiscount;
                            $grandTaxableAmt += $taxableVal;
                            $grandTaxAmt += $taxAmt;
                            $grandInvoiceTotal += $netItemTotal;
                        @endphp
                        <tr>
                            <td class="table-cell border-r border-slate-200 py-1.5">
                                <p class="font-bold text-slate-900 leading-tight">{{ $title }}</p>
                                <p class="text-[8px] text-slate-400 font-semibold uppercase mt-0.5 leading-none">
                                    HSN/SAC: {{ $hsn }} 
                                    @if($item->sku) | SKU: {{ $item->sku }} @endif
                                    @if($item->variant_name) | Variant: {{ $item->variant_name }} @endif
                                </p>
                            </td>
                            <td class="table-cell border-r border-slate-200 text-center font-semibold text-slate-900">{{ $item->quantity }}</td>
                            <td class="table-cell border-r border-slate-200 text-right font-semibold text-slate-900">₹{{ number_format($grossAmt, 2) }}</td>
                            <td class="table-cell border-r border-slate-200 text-right font-semibold text-rose-600">-₹{{ number_format($totalItemDiscount, 2) }}</td>
                            <td class="table-cell border-r border-slate-200 text-right font-semibold text-slate-950">₹{{ number_format($taxableVal, 2) }}</td>
                            <td class="table-cell border-r border-slate-200 text-center font-bold text-slate-700 leading-tight">
                                @if($isIntraState)
                                    CGST: {{ $gstRate / 2 }}%<br>SGST: {{ $gstRate / 2 }}%
                                @else
                                    IGST: {{ $gstRate }}%
                                @endif
                            </td>
                            <td class="table-cell text-right font-bold text-slate-950">₹{{ number_format($netItemTotal, 2) }}</td>
                        </tr>
                    @endforeach
                    
                    {{-- 2. Platform Fee Row --}}
                    @if($platformFee > 0)
                        @php
                            $feeGstRate = 18.0;
                            $feeTaxable = $platformFee / (1 + ($feeGstRate / 100));
                            $feeTaxAmt = $platformFee - $feeTaxable;
                            
                            $grandGrossAmt += $platformFee;
                            $grandTaxableAmt += $feeTaxable;
                            $grandTaxAmt += $feeTaxAmt;
                            $grandInvoiceTotal += $platformFee;
                        @endphp
                        <tr>
                            <td class="table-cell border-r border-slate-200 py-1.5">
                                <p class="font-bold text-slate-900 leading-tight text-slate-800">Platform Facilitation Fee</p>
                                <p class="text-[8px] text-slate-400 font-semibold uppercase mt-0.5 leading-none">SAC: 998599 | Sold By: Remenant Internet</p>
                            </td>
                            <td class="table-cell border-r border-slate-200 text-center font-semibold text-slate-900">1</td>
                            <td class="table-cell border-r border-slate-200 text-right font-semibold text-slate-900">₹{{ number_format($platformFee, 2) }}</td>
                            <td class="table-cell border-r border-slate-200 text-right font-semibold text-rose-600">₹0.00</td>
                            <td class="table-cell border-r border-slate-200 text-right font-semibold text-slate-950">₹{{ number_format($feeTaxable, 2) }}</td>
                            <td class="table-cell border-r border-slate-200 text-center font-bold text-slate-700 leading-tight">
                                @if($isIntraState)
                                    CGST: 9.0%<br>SGST: 9.0%
                                @else
                                    IGST: 18.0%
                                @endif
                            </td>
                            <td class="table-cell text-right font-bold text-slate-950">₹{{ number_format($platformFee, 2) }}</td>
                        </tr>
                    @endif
                    
                    {{-- 3. Shipping / GT Charges Row --}}
                    @if($order->shipping_charge > 0)
                        @php
                            $shipGross = $order->shipping_charge;
                            $shipTaxable = $shipGross; // 0% GST
                            $shipTaxAmt = 0.00;
                            
                            $grandGrossAmt += $shipGross;
                            $grandTaxableAmt += $shipTaxable;
                            $grandTaxAmt += $shipTaxAmt;
                            $grandInvoiceTotal += $shipGross;
                        @endphp
                        <tr>
                            <td class="table-cell border-r border-slate-200 py-1.5">
                                <p class="font-bold text-slate-900 leading-tight">GT Charges (Transport Charges)</p>
                                <p class="text-[8px] text-slate-400 font-semibold uppercase mt-0.5 leading-none">SAC: 996511 | Carrier: Instakart Services</p>
                            </td>
                            <td class="table-cell border-r border-slate-200 text-center font-semibold text-slate-900">1</td>
                            <td class="table-cell border-r border-slate-200 text-right font-semibold text-slate-900">₹{{ number_format($shipGross, 2) }}</td>
                            <td class="table-cell border-r border-slate-200 text-right font-semibold text-rose-600">₹0.00</td>
                            <td class="table-cell border-r border-slate-200 text-right font-semibold text-slate-950">₹{{ number_format($shipTaxable, 2) }}</td>
                            <td class="table-cell border-r border-slate-200 text-center font-bold text-slate-700 leading-tight">
                                GST: 0.0%
                            </td>
                            <td class="table-cell text-right font-bold text-slate-950">₹{{ number_format($shipGross, 2) }}</td>
                        </tr>
                    @endif

                    <!-- Totals Row -->
                    <tr class="bg-slate-50 font-bold text-slate-950 border-t border-slate-200">
                        <td class="table-cell border-r border-slate-200">Total</td>
                        <td class="table-cell border-r border-slate-200 text-center">-</td>
                        <td class="table-cell border-r border-slate-200 text-right">₹{{ number_format($grandGrossAmt, 2) }}</td>
                        <td class="table-cell border-r border-slate-200 text-right text-rose-600">-₹{{ number_format($order->discount_amount + $platformFee, 2) }}</td>
                        <td class="table-cell border-r border-slate-200 text-right">₹{{ number_format($grandTaxableAmt, 2) }}</td>
                        <td class="table-cell border-r border-slate-200 text-center text-[8px] text-slate-400 leading-none">Tax Total:<br>₹{{ number_format($grandTaxAmt, 2) }}</td>
                        <td class="table-cell text-right text-orange-600 text-xs">₹{{ number_format($grandInvoiceTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Grand Total Summary Box -->
            <div class="flex justify-end block-margin">
                <div class="w-64 bg-slate-50/50 border border-slate-200 p-2.5 rounded-lg space-y-1 text-[10px]">
                    <div class="flex justify-between text-slate-500 font-medium">
                        <span>Gross Amount</span>
                        <span class="text-slate-900 font-bold">₹{{ number_format($grandGrossAmt, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500 font-medium">
                        <span>Discounts & Promos</span>
                        <span class="text-rose-600 font-bold">-₹{{ number_format($order->discount_amount + $platformFee, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500 font-medium">
                        <span>Taxable Value</span>
                        <span class="text-slate-900 font-bold">₹{{ number_format($grandTaxableAmt, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500 font-medium">
                        @if($isIntraState)
                            <span>CGST + SGST</span>
                        @else
                            <span>IGST (Integrated Tax)</span>
                        @endif
                        <span class="text-slate-900 font-bold">₹{{ number_format($grandTaxAmt, 2) }}</span>
                    </div>
                    <div class="pt-1.5 border-t border-slate-200 flex justify-between items-center text-xs font-extrabold text-slate-950">
                        <span>Grand Total</span>
                        <span class="text-orange-600 text-sm">₹{{ number_format($grandInvoiceTotal, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Signatures & Policies -->
        <div>
            <div class="grid grid-cols-2 gap-4 items-end border-t border-slate-100 pt-3">
                <div class="text-[8px] text-slate-500 leading-normal border-r border-slate-100 pr-3">
                    <p class="font-bold text-slate-700 uppercase mb-0.5 leading-none">Returns Policy:</p>
                    At {{ $invoiceSettings['company_name'] }} we try to deliver perfectly each and every time. But in the off-chance that you need to return the item, please do so with the original Brand box/price tag, original packing and invoice without which it will be really difficult for us to act on your request. Please help us in helping you. Terms and conditions apply.
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-bold text-slate-900 mb-1 uppercase leading-none">{{ $wName }}</p>
                    <img src="{{ $invoiceSettings['signature'] ? Storage::url($invoiceSettings['signature']) : asset('images/logo/sign.png') }}" class="h-8 w-auto ml-auto mb-0.5">
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider leading-none">Authorized Signatory</p>
                </div>
            </div>

            <!-- Regd Office Address -->
            <div class="mt-4 pt-3 border-t border-slate-100 text-center text-[8px] text-slate-400 leading-none">
                <p>Regd. office: {{ $wName }}, {{ $fullWarehouseAddress }}</p>
                <p class="mt-1 tracking-wider text-[8px] italic leading-none">This is a computer generated tax invoice. No physical signature required. Platform facilitation fee represents services rendered by platform owner.</p>
            </div>
        </div>
    </div>

    <script>
        // Auto print preview
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 800);
        });
    </script>
</body>
</html>
