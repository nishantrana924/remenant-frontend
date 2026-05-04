<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .print-border-none { border: none !important; }
        }
        @page {
            margin: 2cm;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-[800px] mx-auto my-10 bg-white shadow-lg p-10 border border-gray-200">
        <!-- Header -->
        <div class="flex justify-between items-start border-b pb-8 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter">REMENANT</h1>
                <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Health & Wellness Intelligence</p>
                <div class="mt-6 text-sm text-gray-600">
                    <p class="font-bold text-gray-800">Remenant Health Private Limited</p>
                    <p>123 Business Hub, Sector 45</p>
                    <p>Ujjain, Madhya Pradesh - 456001</p>
                    <p>GSTIN: 23AABCR1234Z1Z1</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-4xl font-bold text-gray-800 uppercase tracking-tighter">Tax Invoice</h2>
                <div class="mt-6 text-sm">
                    <p class="text-gray-500 uppercase font-bold text-[10px]">Invoice Number</p>
                    <p class="font-bold text-gray-900 text-lg">#{{ $order->order_number }}</p>
                    <p class="text-gray-500 uppercase font-bold text-[10px] mt-4">Date of Issue</p>
                    <p class="font-bold text-gray-900">{{ $order->created_at->format('d F, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Bill To / Ship To -->
        <div class="grid grid-cols-2 gap-10 mb-10">
            <div>
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Bill To</h3>
                <div class="text-sm">
                    <p class="font-bold text-gray-800 text-base">{{ $order->customer_name }}</p>
                    <p class="text-gray-600 mt-1">{{ $order->address }}</p>
                    <p class="text-gray-600">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                    <p class="text-gray-600 mt-2"><span class="font-bold">Phone:</span> {{ $order->phone }}</p>
                    <p class="text-gray-600"><span class="font-bold">Email:</span> {{ $order->email }}</p>
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
                    <th class="py-4 px-2 text-right">Unit Price</th>
                    <th class="py-4 px-2 text-right">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($order->orderItems as $item)
                <tr class="text-sm">
                    <td class="py-5 px-2">
                        <p class="font-bold text-gray-800">{{ $item->product->title }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">{{ $item->product->subtitle ?? 'Supplement' }}</p>
                    </td>
                    <td class="py-5 px-2 text-center font-bold text-gray-700">{{ $item->quantity }}</td>
                    <td class="py-5 px-2 text-right text-gray-700">₹{{ number_format($item->price) }}</td>
                    <td class="py-5 px-2 text-right font-bold text-gray-900">₹{{ number_format($item->price * $item->quantity) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="flex justify-end border-t pt-8">
            <div class="w-64 space-y-3">
                @php
                    $subtotal = $order->orderItems->sum(fn($i) => $i->price * $i->quantity);
                @endphp
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-bold text-gray-900">₹{{ number_format($subtotal) }}</span>
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

        <!-- Footer -->
        <div class="mt-20 pt-10 border-t border-gray-100 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em]">Thank you for choosing Remenant</p>
            <p class="text-[9px] text-gray-400 mt-2 uppercase tracking-widest">This is a computer generated invoice and does not require a signature.</p>
        </div>

        <!-- Actions -->
        <div class="mt-10 flex justify-center gap-4 no-print">
            <button onclick="window.print()" class="bg-gray-900 text-white px-8 py-3 rounded-sm font-bold text-xs uppercase tracking-widest hover:brightness-110 transition-all">Print Invoice</button>
            <a href="{{ route('order.track', $order->order_number) }}" class="border border-gray-300 text-gray-600 px-8 py-3 rounded-sm font-bold text-xs uppercase tracking-widest hover:bg-gray-50 transition-all">Back to Order</a>
        </div>
    </div>
</body>
</html>
