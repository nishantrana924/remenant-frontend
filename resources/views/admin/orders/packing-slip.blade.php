@php
    $companyName = \App\Models\SiteSetting::getValue('invoice_company_name', 'REMENANT');
    $prefix = \App\Models\SiteSetting::getValue('invoice_prefix', 'REM');
    $displayOrderNumber = rtrim($prefix, '-') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Slip - #{{ $displayOrderNumber }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @verbatim
        @media print {
            .no-print { display: none; }
            body { padding: 0; background: white; }
            .slip-box { border: none; box-shadow: none; width: 100%; margin: 0; padding: 0; }
        }
        @endverbatim
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .slip-box {
            max-width: 800px;
            margin: 40px auto;
            padding: 40px;
            background: white;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <div class="no-print fixed top-6 right-6 flex gap-3">
        <button onclick="window.print()" class="bg-orange-600 text-white px-6 py-2 rounded-full font-bold shadow-lg hover:bg-orange-700 transition-all flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Packing Slip
        </button>
        <button onclick="window.close()" class="bg-white text-slate-600 border border-slate-200 px-6 py-2 rounded-full font-bold shadow-sm hover:bg-slate-50 transition-all">Close</button>
    </div>

    <div class="slip-box">
        <!-- Header -->
        <div class="flex justify-between items-center border-b-2 border-orange-600 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 uppercase tracking-tighter">Packing Slip</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">{{ $companyName }} Fulfillment Center</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-slate-400 uppercase">Order Number</p>
                <p class="text-2xl font-bold text-orange-600">#{{ $displayOrderNumber }}</p>
            </div>
        </div>

        <!-- Shipping Info -->
        <div class="grid grid-cols-2 gap-12 mb-12">
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Ship To</h3>
                <div class="text-sm text-slate-900 space-y-1">
                    <p class="text-lg font-bold">{{ $order->customer_name }}</p>
                    <p class="font-medium">{{ $order->address }}</p>
                    <p class="font-medium">{{ $order->landmark }}</p>
                    <p class="font-medium">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                    <p class="pt-2 font-bold text-orange-600">Tel: {{ $order->phone }}</p>
                </div>
            </div>
            <div class="flex flex-col justify-center">
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Order Date</p>
                        <p class="text-sm font-bold text-slate-900">{{ $order->created_at->format('d F, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Courier Partner</p>
                        <p class="text-sm font-bold text-slate-900 uppercase italic">{{ $order->courier_name ?? 'Awaiting Assignment' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Payment Mode</p>
                        <p class="text-sm font-bold text-slate-900 uppercase">{{ $order->payment_method ?? 'Online' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checklist Table -->
        <div class="space-y-4">
            <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.2em]">Package Contents Checklist</h3>
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-orange-600 text-[10px] font-bold text-white uppercase tracking-widest">
                        <th class="px-6 py-3 rounded-l-lg">Check</th>
                        <th class="px-6 py-3">Item Description</th>
                        <th class="px-6 py-3 text-center">SKU</th>
                        <th class="px-6 py-3 text-right rounded-r-lg">Quantity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td class="px-6 py-6">
                            <div class="h-6 w-6 border-2 border-slate-200 rounded-md"></div>
                        </td>
                        <td class="px-6 py-6">
                            <p class="font-bold text-slate-900 text-sm uppercase">{{ $item->product->title ?? $item->product->name }}</p>
                            @if($item->variant_name)
                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Variant: {{ $item->variant_name }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-6 text-center text-[10px] font-bold text-slate-400 uppercase">
                            {{ $item->sku ?? $item->product->sku ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-6 text-right">
                            <span class="inline-flex items-center justify-center h-10 w-10 bg-orange-50 rounded-full font-bold text-orange-600 text-lg">
                                {{ $item->quantity }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Packing Instructions -->
        <div class="mt-12 p-6 bg-orange-50 rounded-2xl border border-orange-100">
            <h4 class="text-[10px] font-bold text-orange-600 uppercase tracking-widest mb-2">Packing Instructions</h4>
            <p class="text-xs text-slate-600 leading-relaxed">
                1. Verify item name and variant against checklist.<br>
                2. Check for physical damage or manufacturing defects.<br>
                3. Ensure bubble wrap is used for fragile components.<br>
                4. Include the original tax invoice inside the box.
            </p>
        </div>

        <!-- Footer -->
        <div class="mt-20 flex justify-between items-end">
            <div>
                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest mb-6">Packed By</p>
                <div class="w-48 border-b-2 border-slate-100"></div>
            </div>
            <div class="text-right">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $companyName }} Fulfillment Core</p>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 800);
        });
    </script>
</body>
</html>
