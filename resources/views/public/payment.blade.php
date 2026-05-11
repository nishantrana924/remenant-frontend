@extends('public.layouts.app')

@section('title', 'Secure Payment - Remenant')

@section('content')
<div class="min-h-screen bg-[#FDFCFB] py-20 px-4">
    <div class="max-w-xl mx-auto">
        <!-- Brand Header -->
        <div class="text-center mb-12">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-[2rem] bg-orange-500 text-white shadow-2xl shadow-orange-200 mb-6">
                <i data-lucide="shield-check" class="h-8 w-8"></i>
            </div>
            <h1 class="text-3xl font-black italic tracking-tighter uppercase text-slate-900">Secure Payment</h1>
            <p class="text-slate-400 font-bold uppercase tracking-[0.3em] text-[10px] mt-2">Remenant Intelligence Pay</p>
        </div>

        <!-- Payment Card -->
        <div class="bg-white rounded-[3rem] p-8 sm:p-12 shadow-2xl shadow-orange-100 ring-1 ring-black/[0.03] relative overflow-hidden">
            <!-- Order Details -->
            <div class="flex justify-between items-center mb-10 pb-8 border-b border-dashed border-slate-200">
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Order Number</span>
                    <span class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $order->order_number }}</span>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Amount to Pay</span>
                    <span class="text-2xl font-black text-orange-500">₹{{ number_format($order->total_amount) }}</span>
                </div>
            </div>

            <!-- Simulation Steps -->
            <div x-data="{ step: 'options', processing: false }" class="space-y-8">
                @if(isset($is_sandbox))
                    <!-- SANDBOX / MOCK MODE UI -->
                    <div x-show="step === 'options'" class="space-y-4">
                        <div class="p-4 bg-orange-50 rounded-2xl border border-orange-100 mb-6">
                            <p class="text-[10px] font-black text-orange-600 uppercase tracking-widest flex items-center gap-2">
                                <i data-lucide="info" class="h-3 w-3"></i>
                                Sandbox Mode Active
                            </p>
                            <p class="text-[9px] font-bold text-orange-500 mt-1 uppercase">Click any method to simulate a successful payment for testing.</p>
                        </div>

                        <button @click="step = 'processing'; setTimeout(() => { finalizeMockPayment() }, 2000)" class="w-full group flex items-center justify-between p-6 rounded-2xl bg-slate-50 border-2 border-transparent hover:border-orange-500 hover:bg-orange-50 transition-all duration-300">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-white flex items-center justify-center text-slate-400 group-hover:text-orange-500 transition-colors shadow-sm">
                                    <i data-lucide="smartphone" class="h-6 w-6"></i>
                                </div>
                                <div class="text-left">
                                    <span class="block text-sm font-black text-slate-900 uppercase">UPI / QR (Sandbox)</span>
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">PhonePe, Google Pay, Paytm</span>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="h-5 w-5 text-slate-300 group-hover:text-orange-500 transition-all"></i>
                        </button>

                        <button @click="step = 'processing'; setTimeout(() => { finalizeMockPayment() }, 2000)" class="w-full group flex items-center justify-between p-6 rounded-2xl bg-slate-50 border-2 border-transparent hover:border-orange-500 hover:bg-orange-50 transition-all duration-300">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-white flex items-center justify-center text-slate-400 group-hover:text-orange-500 transition-colors shadow-sm">
                                    <i data-lucide="credit-card" class="h-6 w-6"></i>
                                </div>
                                <div class="text-left">
                                    <span class="block text-sm font-black text-slate-900 uppercase">Cards (Sandbox)</span>
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Visa, Mastercard, RuPay</span>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="h-5 w-5 text-slate-300 group-hover:text-orange-500 transition-all"></i>
                        </button>
                    </div>

                    <form id="mock-payment-form" action="{{ route('checkout.payment.mock', $order->order_number) }}" method="POST" class="hidden">
                        @csrf
                    </form>

                    <script>
                        function finalizeMockPayment() {
                            document.getElementById('mock-payment-form').submit();
                        }
                    </script>
                @else
                    <!-- REAL RAZORPAY MODE -->
                    <div x-show="step === 'options'" class="text-center py-8">
                        <button id="rzp-button1" class="w-full py-5 rounded-2xl bg-slate-900 text-white font-black uppercase tracking-[0.2em] text-sm shadow-2xl hover:brightness-110 active:scale-95 transition-all flex items-center justify-center gap-3">
                            <i data-lucide="shield-check" class="h-5 w-5 text-emerald-500"></i>
                            Pay via Razorpay
                        </button>
                    </div>

                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                    <script>
                        var options = {
                            "key": "{{ $razorpay_key }}",
                            "amount": "{{ $order->total_amount * 100 }}",
                            "currency": "INR",
                            "name": "Remenant Health",
                            "description": "Payment for Order #{{ $order->order_number }}",
                            "image": "{{ asset('images/logo/remenant-health-logo.png') }}",
                            "order_id": "{{ $razorpay_order_id }}",
                            "handler": function (response){
                                var form = document.createElement('form');
                                form.method = 'POST';
                                form.action = "{{ route('checkout.payment.verify') }}";
                                
                                var inputs = {
                                    '_token': "{{ csrf_token() }}",
                                    'razorpay_payment_id': response.razorpay_payment_id,
                                    'razorpay_order_id': response.razorpay_order_id,
                                    'razorpay_signature': response.razorpay_signature,
                                    'order_number': "{{ $order->order_number }}"
                                };

                                for (var key in inputs) {
                                    var input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = key;
                                    input.value = inputs[key];
                                    form.appendChild(input);
                                }

                                document.body.appendChild(form);
                                form.submit();
                            },
                            "prefill": {
                                "name": "{{ $order->customer_name }}",
                                "email": "{{ $order->email }}",
                                "contact": "{{ $order->phone }}"
                            },
                            "theme": { "color": "#FF6B00" }
                        };
                        var rzp1 = new Razorpay(options);
                        document.getElementById('rzp-button1').onclick = function(e){
                            rzp1.open();
                            e.preventDefault();
                        }
                    </script>
                @endif

                <!-- Processing Simulation -->
                <div x-show="step === 'processing'" class="text-center py-10">
                    <div class="relative inline-block mb-8">
                        <div class="h-24 w-24 rounded-[2.5rem] border-4 border-orange-100 animate-[spin_3s_linear_infinite]"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i data-lucide="lock" class="h-8 w-8 text-orange-500 animate-pulse"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Securing Connection...</h3>
                    <p class="text-sm font-bold text-slate-400 mt-2">Processing your transaction securely.</p>
                </div>
            </div>
        </div>

        <!-- Trust Signals -->
        <div class="mt-12 flex items-center justify-center gap-12 opacity-40 grayscale">
            <img src="{{ asset('images/icons/visa.png') }}" alt="Visa" class="h-4 object-contain">
            <img src="{{ asset('images/icons/mastercard.png') }}" alt="Mastercard" class="h-6 object-contain">
            <img src="{{ asset('images/icons/pci.png') }}" alt="PCI" class="h-8 object-contain">
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endsection
