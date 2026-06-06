<!-- Rate Calculator Modal -->
<div id="rate-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] hidden flex items-start justify-center p-4 overflow-y-auto pt-10 pb-10">
    <div class="bg-white rounded-[2rem] w-full max-w-2xl shadow-2xl overflow-hidden animate-in zoom-in duration-300 flex flex-col max-h-[85vh]">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-white shrink-0">
            <div>
                <h3 class="text-xl font-bold text-slate-900 uppercase tracking-tight">Shipping Rate Calculator</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Real-time Courier Price Comparison</p>
            </div>
            <button onclick="closeRateCalculator()" class="h-10 w-10 rounded-xl hover:bg-slate-50 flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
            </button>
        </div>
        
        <div class="p-8 overflow-y-auto flex-1 custom-scrollbar">
            <form id="rate-form" class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Origin Pincode</label>
                    <input type="text" name="origin_pincode" required class="saas-input" value="110001" placeholder="Pickup Pincode">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Destination Pincode</label>
                    <input type="text" name="destination_pincode" required class="saas-input" placeholder="Delivery Pincode">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Weight (in kg)</label>
                    <input type="number" step="0.1" name="weight" required class="saas-input" value="0.5" placeholder="e.g. 0.5">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Payment Mode</label>
                    <select name="payment_type" class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-2.5 text-sm font-bold">
                        <option value="prepaid">Prepaid</option>

                    </select>
                </div>
                <input type="hidden" name="order_amount" value="1000">
                <div class="col-span-2">
                    <button type="submit" class="w-full saas-btn-primary py-3">Fetch Best Rates</button>
                </div>
            </form>

            <div id="rate-results" class="mt-8 space-y-4">
                <!-- Results will be injected here -->
            </div>
        </div>
    </div>
</div>

<script>
function openRateCalculator() {
    document.getElementById('rate-modal').classList.remove('hidden');
    document.getElementById('rate-modal').classList.add('flex');
}

function closeRateCalculator() {
    document.getElementById('rate-modal').classList.add('hidden');
    document.getElementById('rate-modal').classList.remove('flex');
}

document.getElementById('rate-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button');
    const container = document.getElementById('rate-results');
    
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide=\"refresh-cw\" class=\"w-4 h-4 animate-spin mx-auto\"></i>';
    lucide.createIcons();
    container.innerHTML = '';

    try {
        const formData = new FormData(e.target);
        const res = await fetch('{{ route('admin.logistics.calculate-rates') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const data = await res.json();
        
        if (data.success) {
            data.data.forEach(rate => {
                container.innerHTML += `
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-blue-500 transition-all bg-white shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                <i data-lucide="truck" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-900 uppercase tracking-tight">${rate.name || 'Courier'}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Est. Delivery: ${rate.edd || '-'}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600 tracking-tighter">₹${rate.total_charges}</p>
                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest">Total Cost</span>
                        </div>
                    </div>
                `;
            });
            lucide.createIcons();
        } else {
            container.innerHTML = \`<div class=\"p-4 bg-rose-50 text-rose-500 rounded-xl text-xs font-bold\">\${data.message}</div>\`;
        }
    } catch (err) {
        container.innerHTML = '<div class=\"p-4 bg-rose-50 text-rose-500 rounded-xl text-xs font-bold\">Something went wrong.</div>';
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Fetch Best Rates';
    }
});
</script>
