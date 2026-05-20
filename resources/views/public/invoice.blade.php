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
    
    $displayInvoiceNumber = $invoiceSettings['prefix'] 
        ? rtrim($invoiceSettings['prefix'], '-') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) 
        : str_pad($order->id, 5, '0', STR_PAD_LEFT);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $displayInvoiceNumber }}</title>
    <link rel="icon" href="{{ asset('images/logo/remenant-health-favicon.jpg') }}" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .print-border-none { border: none !important; }
            .invoice-container { box-shadow: none !important; border: none !important; margin: 0 !important; width: 100% !important; max-width: none !important; padding: 0 !important; }
            @page { margin: 0; }
        }
        
        /* Layouts */
        .size-a4 {
            max-width: 800px;
            padding: 40px;
        }
        .size-thermal {
            max-width: 400px;
            padding: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="invoice-container mx-auto my-10 bg-white shadow-lg border border-gray-200 {{ $invoiceSettings['page_size'] == 'Thermal' ? 'size-thermal' : 'size-a4' }}">
        <!-- Header -->
        <div class="flex justify-between items-start border-b pb-8 mb-8">
            <div>
                @if($invoiceSettings['logo'])
                    <img src="{{ Storage::url($invoiceSettings['logo']) }}" class="h-12 w-auto mb-4">
                @endif

                @if($invoiceSettings['show_company'] == '1')
                    <h1 class="text-3xl font-black text-slate-900 tracking-tighter">{{ $invoiceSettings['company_name'] }}</h1>
                    <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Authorized Seller</p>
                @endif

                <div class="mt-6 text-sm text-gray-600 leading-relaxed">
                    @php
                        $wName = ($warehouse && $warehouse->name && $warehouse->name !== 'N/A') ? $warehouse->name : 'Remenant Health Private Limited';
                        $wAddress = ($warehouse && $warehouse->address && $warehouse->address !== 'N/A') ? $warehouse->address : '123 Business Hub, Sector 45';
                        $wAddress2 = ($warehouse && $warehouse->address_2 && $warehouse->address_2 !== 'N/A') ? $warehouse->address_2 : '';
                        $wCity = ($warehouse && $warehouse->city && $warehouse->city !== 'N/A') ? $warehouse->city : 'Ujjain';
                        $wState = ($warehouse && $warehouse->state && $warehouse->state !== 'N/A') ? $warehouse->state : 'Madhya Pradesh';
                        $wPincode = ($warehouse && $warehouse->pincode && $warehouse->pincode !== '000000') ? $warehouse->pincode : '456001';
                        $wGst = ($warehouse && $warehouse->gst_number && $warehouse->gst_number !== 'N/A') ? $warehouse->gst_number : '23AABCR1234Z1Z1';
                    @endphp

                    <p class="font-bold text-gray-800">{{ $wName }}</p>
                    <p>{{ trim($wAddress . ' ' . $wAddress2) }}</p>
                    <p>{{ $wCity }}, {{ $wState }} - {{ $wPincode }}</p>
                    @if($wGst)
                        <p class="mt-2"><span class="font-bold text-gray-400 uppercase text-[10px]">GSTIN:</span> <span class="font-bold text-gray-800">{{ $wGst }}</span></p>
                    @endif

                    @if($invoiceSettings['page_size'] != 'Thermal')
                        @foreach($invoiceSettings['custom_fields'] as $field)
                            <p><span class="font-bold text-gray-400 uppercase text-[10px]">{{ $field['key'] }}:</span> {{ $field['value'] }}</p>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-4xl font-bold text-gray-800 uppercase tracking-tighter">Tax Invoice</h2>
                <div class="mt-6 text-sm">
                    <p class="text-gray-500 uppercase font-bold text-[10px]">Invoice Number</p>
                    <p class="font-bold text-gray-900 text-lg">#{{ $displayInvoiceNumber }}</p>
                    <p class="text-gray-500 uppercase font-bold text-[10px] mt-4">Date of Issue</p>
                    <p class="font-bold text-gray-900">{{ $order->created_at->format('d F, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Bill To / Ship To -->
        <div class="grid grid-cols-2 gap-10 mb-10 {{ $invoiceSettings['page_size'] == 'Thermal' ? 'gap-4 mb-4' : '' }}">
            <div>
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Bill To</h3>
                <div class="text-sm">
                    <p class="font-bold text-gray-800 text-base">{{ $order->customer_name }}</p>
                    <p class="text-gray-600 mt-1">{{ $order->address }}</p>
                    <p class="text-gray-600">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                    <p class="text-gray-600 mt-2"><span class="font-bold">Phone:</span> {{ $order->phone }}</p>
                </div>
            </div>
            <div class="bg-gray-50 p-6 rounded-sm">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Ship To</h3>
                <div class="text-sm">
                    <p class="font-bold text-gray-800">{{ $order->customer_name }}</p>
                    <p class="text-gray-600 mt-1">{{ $order->address }}</p>
                    <p class="text-gray-600">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-left mb-10">
            <thead>
                <tr class="border-b-2 border-gray-900 text-[10px] font-black uppercase tracking-widest text-gray-500">
                    <th class="py-4 px-2">Description</th>
                    <th class="py-4 px-2 text-center">Qty</th>
                    <th class="py-4 px-2 text-right">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($order->orderItems as $item)
                <tr class="text-sm">
                    <td class="py-5 px-2 flex items-center gap-4">
                        @if($item->product && $item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->title }}" class="h-10 w-10 object-cover rounded shadow-sm border border-gray-100">
                        @endif
                        <p class="font-bold text-gray-800">{{ $item->product->title ?? 'Unknown Product' }}</p>
                    </td>
                    <td class="py-5 px-2 text-center font-bold text-gray-700">{{ $item->quantity }}</td>
                    <td class="py-5 px-2 text-right font-bold text-gray-900">₹{{ number_format($item->price * $item->quantity) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="flex justify-end border-t pt-8">
            <div class="w-64 space-y-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-bold text-gray-900">₹{{ number_format($order->total_amount - ($order->shipping_charge ?? 0) + ($order->discount_amount ?? 0)) }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Shipping</span>
                    <span class="font-bold text-gray-900">₹{{ number_format($order->shipping_charge ?? 0) }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Discount</span>
                    <span class="font-bold text-green-600">-₹{{ number_format($order->discount_amount ?? 0) }}</span>
                </div>
                <div class="flex justify-between pt-4 border-t-2 border-gray-900">
                    <span class="text-base font-black uppercase tracking-widest">Total</span>
                    <span class="text-xl font-black text-blue-600">₹{{ number_format($order->total_amount) }}</span>
                </div>
            </div>
        </div>

        <!-- Signature -->
        @if($invoiceSettings['signature'])
        <div class="mt-12 flex justify-end">
            <div class="text-center">
                <img src="{{ Storage::url($invoiceSettings['signature']) }}" class="h-16 w-auto mx-auto mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-t border-gray-100 pt-2">Authorized Signature</p>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="mt-20 pt-10 border-t border-gray-100 text-center">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em]">Thank you for choosing Remenant</p>
            <p class="text-[9px] text-gray-400 mt-2 uppercase tracking-widest">This is a computer generated invoice and does not require a signature.</p>
        </div>

        <!-- Actions -->
        <div class="mt-10 flex justify-center gap-4 no-print pb-10">
            <button onclick="window.print()" class="bg-gray-900 text-white px-8 py-3 rounded-sm font-bold text-xs uppercase tracking-widest hover:brightness-110 transition-all">Print Invoice</button>
            <a href="{{ route('order.track', $order->order_number) }}" class="border border-gray-300 text-gray-600 px-8 py-3 rounded-sm font-bold text-xs uppercase tracking-widest hover:bg-gray-50 transition-all">Back to Order</a>
        </div>
    </div>
</body>
</html>
