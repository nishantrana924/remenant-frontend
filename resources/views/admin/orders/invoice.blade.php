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
    <title>Tax Invoice - #{{ $displayInvoiceNumber }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
            body { padding: 0; background: white; }
            .invoice-box { border: none; box-shadow: none; width: 100%; margin: 0 !important; padding: 0 !important; }
            @page { margin: 0; }
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .invoice-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin: 40px auto;
        }
        
        /* A4 Specific */
        .size-a4 {
            max-width: 800px;
            padding: 40px;
        }

        /* Thermal Specific (4x6 approx) */
        .size-thermal {
            max-width: 400px;
            padding: 20px;
            font-size: 12px;
            border-radius: 0;
            margin: 0 auto;
        }
        .size-thermal h1 { font-size: 24px; }
        .size-thermal .text-4xl { font-size: 24px; }
        .size-thermal .text-2xl { font-size: 18px; }
        .size-thermal .mt-20 { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="no-print fixed top-6 right-6 flex gap-3">
        <button onclick="window.print()" class="bg-orange-600 text-white px-6 py-2 rounded-full font-bold shadow-lg hover:bg-orange-700 transition-all flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Invoice
        </button>
        <button onclick="window.close()" class="bg-white text-slate-600 border border-slate-200 px-6 py-2 rounded-full font-bold shadow-sm hover:bg-slate-50 transition-all">Close</button>
    </div>

    <div class="invoice-box {{ $invoiceSettings['page_size'] == 'Thermal' ? 'size-thermal' : 'size-a4' }}">
        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-orange-600 pb-8 mb-8">
            <div>
                @if($invoiceSettings['logo'])
                    <img src="{{ Storage::url($invoiceSettings['logo']) }}" class="h-12 w-auto mb-4">
                @endif

                @if($invoiceSettings['show_company'] == '1')
                    <h1 class="text-4xl font-extrabold text-slate-900 tracking-tighter uppercase">{{ $invoiceSettings['company_name'] }}</h1>
                    <p class="text-xs font-bold text-orange-600 uppercase tracking-widest mt-1">Authorized Seller</p>
                @endif

                <div class="mt-6 text-sm text-slate-500 leading-relaxed">
                    @php
                        $wName = ($warehouse && $warehouse->name && $warehouse->name !== 'N/A') ? $warehouse->name : 'Remenant Official Store';
                        $wContact = ($warehouse && $warehouse->contact_person && $warehouse->contact_person !== 'N/A') ? $warehouse->contact_person : 'Warehouse Manager';
                        $wPhone = ($warehouse && $warehouse->phone && $warehouse->phone !== '0000000000') ? $warehouse->phone : '9876543210';
                        $wAddress = ($warehouse && $warehouse->address && $warehouse->address !== 'N/A') ? $warehouse->address : '123 Tech Park, Silicon Valley';
                        $wAddress2 = ($warehouse && $warehouse->address_2 && $warehouse->address_2 !== 'N/A') ? $warehouse->address_2 : '';
                        $wCity = ($warehouse && $warehouse->city && $warehouse->city !== 'N/A') ? $warehouse->city : 'Pune';
                        $wState = ($warehouse && $warehouse->state && $warehouse->state !== 'N/A') ? $warehouse->state : 'Maharashtra';
                        $wPincode = ($warehouse && $warehouse->pincode && $warehouse->pincode !== '000000') ? $warehouse->pincode : '411001';
                        $wGst = ($warehouse && $warehouse->gst_number && $warehouse->gst_number !== 'N/A' && $warehouse->gst_number !== '') ? $warehouse->gst_number : '27AAAAA0000A1Z5';
                    @endphp

                    <p class="font-bold text-slate-900">{{ $wContact }} ({{ $wPhone }})</p>
                    <p>{{ trim($wAddress . ' ' . $wAddress2) }}</p>
                    <p>{{ $wCity }}, {{ $wState }} - {{ $wPincode }}</p>
                    @if($wGst)
                        <p class="mt-2"><span class="font-bold text-slate-400 uppercase text-[10px]">GSTIN:</span> <span class="font-bold text-slate-900">{{ $wGst }}</span></p>
                    @endif
                    
                    @if($invoiceSettings['page_size'] != 'Thermal' && is_array($invoiceSettings['custom_fields']))
                        @foreach($invoiceSettings['custom_fields'] as $field)
                            <p><span class="font-bold text-slate-400 uppercase text-[10px]">{{ $field['key'] }}:</span> {{ $field['value'] }}</p>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-orange-600 uppercase">Tax Invoice</h2>
                <div class="mt-4 text-sm text-slate-500">
                    <p><span class="font-bold text-slate-400 uppercase text-[10px]">Invoice No:</span> <span class="text-slate-900 font-bold">#{{ $displayInvoiceNumber }}</span></p>
                    <p><span class="font-bold text-slate-400 uppercase text-[10px]">Date:</span> <span class="text-slate-900 font-bold">{{ $order->created_at->format('d M, Y') }}</span></p>
                    <p><span class="font-bold text-slate-400 uppercase text-[10px]">Order ID:</span> <span class="text-slate-900 font-bold">{{ $order->id }}</span></p>
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="grid grid-cols-2 gap-12 mb-12 {{ $invoiceSettings['page_size'] == 'Thermal' ? 'gap-4 mb-4' : '' }}">
            <div>
                <h3 class="text-[10px] font-bold text-orange-600 uppercase tracking-[0.2em] mb-3">Bill To</h3>
                <div class="text-sm text-slate-600 space-y-1">
                    <p class="text-lg font-bold text-slate-900">{{ $order->customer_name }}</p>
                    <p>{{ $order->phone }}</p>
                    <p>{{ $order->address }}</p>
                    <p>{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                </div>
            </div>
            <div>
                <h3 class="text-[10px] font-bold text-orange-600 uppercase tracking-[0.2em] mb-3">Ship To</h3>
                <div class="text-sm text-slate-600 space-y-1">
                    <p class="text-lg font-bold text-slate-900">{{ $order->customer_name }}</p>
                    <p>{{ $order->address }}</p>
                    <p>{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                    <p class="pt-2 font-bold text-slate-900">Phone: {{ $order->phone }}</p>
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-left mb-12 {{ $invoiceSettings['page_size'] == 'Thermal' ? 'mb-4' : '' }}">
            <thead>
                <tr class="border-b-2 border-orange-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <th class="py-4">Item Details</th>
                    <th class="py-4 text-center">Qty</th>
                    <th class="py-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($order->orderItems as $item)
                <tr>
                    <td class="py-6 {{ $invoiceSettings['page_size'] == 'Thermal' ? 'py-2' : '' }} flex items-center gap-4">
                        @if($item->product && $item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-12 w-12 object-cover rounded-md shadow-sm border border-slate-100">
                        @endif
                        <div>
                            <p class="font-bold text-slate-900 uppercase text-sm">{{ $item->product->name ?? 'Unknown Product' }}</p>
                            @if($item->variant_name)
                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Variant: {{ $item->variant_name }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="py-6 text-center text-sm text-slate-900 font-bold {{ $invoiceSettings['page_size'] == 'Thermal' ? 'py-2' : '' }}">{{ $item->quantity }}</td>
                    <td class="py-6 text-right text-sm text-slate-900 font-bold {{ $invoiceSettings['page_size'] == 'Thermal' ? 'py-2' : '' }}">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="flex justify-end">
            <div class="w-72 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400 font-bold uppercase text-[10px]">Subtotal</span>
                    <span class="text-slate-900 font-bold">₹{{ number_format($order->total_amount - $order->shipping_charge + $order->discount_amount, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400 font-bold uppercase text-[10px]">Discount</span>
                    <span class="text-rose-600 font-bold">-₹{{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400 font-bold uppercase text-[10px]">Shipping</span>
                    <span class="text-slate-900 font-bold">₹{{ number_format($order->shipping_charge, 2) }}</span>
                </div>
                <div class="pt-3 border-t-2 border-orange-600 flex justify-between">
                    <span class="text-slate-900 font-bold uppercase text-xs">Total Amount</span>
                    <span class="text-2xl font-bold text-orange-600 tracking-tighter">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Signature -->
        @if($invoiceSettings['signature'])
        <div class="mt-12 flex justify-end">
            <div class="text-center">
                <img src="{{ Storage::url($invoiceSettings['signature']) }}" class="h-16 w-auto mx-auto mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-t border-slate-100 pt-2">Authorized Signature</p>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="mt-20 pt-12 border-t border-slate-100 text-center {{ $invoiceSettings['page_size'] == 'Thermal' ? 'mt-8 pt-4' : '' }}">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Thank you for choosing Remenant Official</p>
            <p class="text-[10px] text-slate-300 mt-2 uppercase tracking-tighter italic">This is a computer generated invoice and does not require a physical signature.</p>
        </div>
    </div>
</body>
</html>
