@extends('public.layouts.app')

@section('title', 'Secure Payment - Remenant')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-slate-50 py-12 px-4">
    <div class="w-full max-w-sm">
        
        <!-- Payment Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
            
            <div class="text-center mb-6">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-orange-600 mb-4">
                    <i data-lucide="credit-card" class="h-6 w-6"></i>
                </div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Complete Payment</h1>
                <p class="text-xs text-slate-500 mt-1">Order #{{ $order->order_number }}</p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-4 flex justify-between items-center mb-8 border border-slate-100">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Amount</span>
                <span class="text-xl font-black text-slate-900">₹{{ number_format($order->total_amount) }}</span>
            </div>

            <div x-data="{ step: 'options' }">
                @if(isset($is_sandbox))
                    <!-- Sandbox Mode -->
                    <div x-show="step === 'options'" class="space-y-3">
                        <div class="text-[10px] text-center font-bold text-orange-500 bg-orange-50 p-2 rounded-lg mb-4">
                            Test Mode Active
                        </div>
                        <button @click="step = 'processing'; document.getElementById('mock-payment-form').submit();" class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-bold transition-all shadow-lg">
                            Simulate Payment
                        </button>
                    </div>
                    <form id="mock-payment-form" action="{{ route('checkout.payment.mock', $order->order_number) }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    <!-- Real Mode -->
                    <div x-show="step === 'options'">
                        <button id="rzp-button1" class="w-full py-4 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-sm font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-orange-200">
                            <i data-lucide="lock" class="h-4 w-4"></i>
                            Pay Securely
                        </button>
                    </div>

                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                    <script>
                        var options = {
                            "key": "{{ $razorpay_key }}",
                            "amount": "{{ $order->total_amount * 100 }}",
                            "currency": "INR",
                            "name": "Remenant",
                            "description": "Order #{{ $order->order_number }}",
                            "image": "{{ asset('images/logo/remenant-health-logo.png') }}",
                            "order_id": "{{ $razorpay_order_id }}",
                            "handler": function (response){
                                // Update Alpine step to show processing spinner safely
                                var el = document.querySelector('[x-data]');
                                if (el && el.__x && el.__x.$data) {
                                    el.__x.$data.step = 'processing';
                                } else if (window.Alpine) {
                                    try {
                                        window.Alpine.$data(el).step = 'processing';
                                    } catch(e) {}
                                } else {
                                    var opt = document.querySelector('[x-show="step === \'options\'"]');
                                    var prc = document.querySelector('[x-show="step === \'processing\'"]');
                                    if (opt) opt.style.display = 'none';
                                    if (prc) prc.style.display = 'block';
                                }

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

                <!-- Processing -->
                <div x-show="step === 'processing'" class="text-center py-6 hidden" :class="{'hidden': step !== 'processing'}">
                    <div class="h-8 w-8 rounded-full border-2 border-orange-200 border-t-orange-600 animate-spin mx-auto mb-3"></div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Processing...</p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-center gap-4 opacity-30 grayscale">
            <i data-lucide="shield-check" class="h-5 w-5"></i>
            <span class="text-[10px] font-bold uppercase tracking-widest flex items-center">SSL Secured</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
