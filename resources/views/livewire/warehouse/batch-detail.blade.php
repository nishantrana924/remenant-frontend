<div class="space-y-6">
    <x-slot name="title">Batch #{{ $batch->id }}</x-slot>

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.warehouse.batches.index') }}" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                Batch #{{ $batch->id }}
                @if($isParallel)
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">
                        Read Only (Parallel Mode)
                    </span>
                @endif
            </h1>
        </div>
        
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <!-- Health Indicator -->
            @php
                $healthColors = [
                    'healthy' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                    'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                    'frozen' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                    'manual_review' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
                ];
                $hColor = $healthColors[$healthStatus] ?? $healthColors['healthy'];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium {{ $hColor }}">
                <span class="w-2 h-2 mr-2 rounded-full bg-current opacity-75"></span>
                {{ ucfirst(str_replace('_', ' ', $healthStatus)) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content Left Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Batch Summary Card -->
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Batch Summary</h3>
                </div>
                <div class="border-t border-gray-200 dark:border-slate-700 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200 dark:sm:divide-slate-700">
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Batch Signature</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2 font-mono">{{ $batch->batch_signature ?? 'N/A' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type / Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                <span class="capitalize mr-2">{{ $batch->batch_type }}</span>
                                <x-warehouse.status-badge :status="$batch->status" />
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Capacity Metrics</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                {{ $batch->total_orders }} Orders &bull; {{ $batch->total_units }} Units &bull; {{ number_format($batch->total_weight ?? 0, 2) }} kg
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Logistics Routing</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                <div class="flex flex-col space-y-1">
                                    <span>Suggested: <span class="font-medium">{{ $batch->suggestedCourier->name ?? 'System Default' }}</span></span>
                                    <span>Assigned: <span class="font-medium text-blue-600 dark:text-blue-400">{{ $batch->assignedCourier->name ?? 'Pending Assignment' }}</span></span>
                                </div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Visual Packing Grid -->
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Visual Packing Grid</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Snapshot data representation for warehouse staff.</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($batch->orders as $order)
                            @foreach($order->orderItems as $item)
                                <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 flex flex-col items-center text-center hover:border-blue-300 dark:hover:border-blue-600 transition-colors">
                                    @if($item->product_image)
                                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-24 h-24 object-cover rounded-md mb-3 shadow-sm">
                                    @else
                                        <div class="w-24 h-24 bg-gray-100 dark:bg-slate-700 rounded-md mb-3 flex items-center justify-center text-gray-400 dark:text-slate-500">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L28 20M6 12a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                    @endif
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ $item->sku }}</span>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white line-clamp-2 mb-2">{{ $item->product_name }}</h4>
                                    <div class="mt-auto w-full flex justify-between items-center text-sm border-t border-gray-100 dark:border-slate-700 pt-2">
                                        <span class="font-bold text-blue-600 dark:text-blue-400">Qty: {{ $item->quantity }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">{{ number_format($item->weight ?? 0, 2) }} kg</span>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Assigned Orders</h3>
                </div>
                <x-warehouse.table>
                    <x-slot name="header">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Value</th>
                        </tr>
                    </x-slot>
                    @foreach($batch->orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400">#{{ $order->order_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $order->customer_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><x-warehouse.status-badge :status="$order->status" /></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ config('app.currency', '₹') }}{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </x-warehouse.table>
            </div>
        </div>

        <!-- Sidebar Right Column -->
        <div class="space-y-6">
            
            <!-- Lock Information Panel -->
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Lock Status
                    </h3>
                    @if(!is_null($batch->awb_generated_at))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                            Batch Frozen
                        </span>
                    @endif
                </div>
                <div class="p-4">
                    @error('lock') <div class="mb-3 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 p-2 rounded">{{ $message }}</div> @enderror
                    @error('mutation') <div class="mb-3 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 p-2 rounded">{{ $message }}</div> @enderror

                    @if(!is_null($batch->awb_generated_at))
                        <div class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                            Lock operations are disabled. The batch has been frozen for fulfillment.
                        </div>
                    @else
                        @if($batch->locked_at && $batch->locked_by_user_id)
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                        {{ substr($batch->lockedByUser->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $batch->lockedByUser->name ?? 'System User' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Locked at: {{ \Carbon\Carbon::parse($batch->locked_at)->format('H:i:s') }}</p>
                                        <p class="text-xs text-red-500 dark:text-red-400 mt-1">
                                            Expires: {{ \Carbon\Carbon::parse($batch->locked_at)->addMinutes(config('warehouse.lock_timeout_minutes', 15))->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                @if(!$isParallel && $batch->locked_by_user_id === auth()->id())
                                    <button wire:click="releaseLock" wire:loading.attr="disabled" wire:target="releaseLock" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                        Unlock
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4 flex flex-col items-center justify-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-slate-700 dark:text-gray-300 mb-4">
                                    Unlocked
                                </span>
                                @if(!$isParallel)
                                    <button wire:click="acquireLock" wire:loading.attr="disabled" wire:target="acquireLock" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        Acquire Lock
                                    </button>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Operations Panel -->
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Operations
                    </h3>
                </div>
                <div class="p-4 space-y-4">
                    @error('fulfillment') <div class="mb-3 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 p-2 rounded">{{ $message }}</div> @enderror
                    @if (session()->has('fulfillment_success'))
                        <div class="mb-3 text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 p-2 rounded">{{ session('fulfillment_success') }}</div>
                    @endif

                    <!-- Pre-Fulfillment Options -->
                    @if(is_null($batch->awb_generated_at))
                        <!-- Assign Courier -->
                        <div>
                            <label for="courier" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Courier</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <select wire:model="selectedCourierId" id="courier" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white disabled:opacity-50" @if($isParallel || $batch->locked_by_user_id !== auth()->id()) disabled @endif>
                                    <option value="">Select a courier...</option>
                                    @foreach($availableCouriers as $courier)
                                        <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                    @endforeach
                                </select>
                                <button wire:click="assignCourier" wire:loading.attr="disabled" wire:target="assignCourier" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed" @if($isParallel || $batch->locked_by_user_id !== auth()->id()) disabled @endif>
                                    Update
                                </button>
                            </div>
                            @error('selectedCourierId') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Assign Weight -->
                        <div class="mt-4">
                            <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Weight (kg)</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input wire:model="assignedWeight" type="number" step="0.1" id="weight" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white disabled:opacity-50" placeholder="0.0" @if($isParallel || $batch->locked_by_user_id !== auth()->id()) disabled @endif>
                                <button wire:click="assignWeight" wire:loading.attr="disabled" wire:target="assignWeight" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed" @if($isParallel || $batch->locked_by_user_id !== auth()->id()) disabled @endif>
                                    Update
                                </button>
                            </div>
                            @error('assignedWeight') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    @else
                        <!-- Frozen State Display -->
                        <div class="bg-gray-50 dark:bg-slate-900/50 p-3 rounded text-sm text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-slate-700 text-center mb-4">
                            Modifying courier and weight is locked. The batch is currently in fulfillment.
                        </div>
                    @endif

                    <hr class="border-gray-200 dark:border-slate-700 my-4">

                    <!-- Fulfillment Executions -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Fulfillment Jobs</h4>
                        
                        <!-- Generate AWB -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Airway Bills (AWB)</span>
                            @if(is_null($batch->awb_generated_at))
                                <button wire:click="generateAWB" wire:loading.attr="disabled" wire:target="generateAWB" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed" @if($isParallel || $batch->locked_by_user_id !== auth()->id()) disabled @endif>
                                    Generate
                                </button>
                            @else
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Generated
                                </span>
                            @endif
                        </div>

                        <!-- Generate Labels -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Shipping Labels</span>
                            @if(is_null($batch->labels_generated_at))
                                <button wire:click="generateLabels" wire:loading.attr="disabled" wire:target="generateLabels" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed" @if($isParallel || $batch->locked_by_user_id !== auth()->id() || is_null($batch->awb_generated_at)) disabled @endif>
                                    Generate
                                </button>
                            @else
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Generated
                                </span>
                            @endif
                        </div>

                        <!-- Generate Slips -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Packing Slips</span>
                            @if(is_null($batch->slips_printed_at))
                                <button wire:click="generateSlips" wire:loading.attr="disabled" wire:target="generateSlips" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed" @if($isParallel || $batch->locked_by_user_id !== auth()->id()) disabled @endif>
                                    Generate
                                </button>
                            @else
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Generated
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr class="border-gray-200 dark:border-slate-700 my-4">

                    <!-- Logistics Handover -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Logistics Handover</h4>
                        
                        <!-- Schedule Pickup -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Ready For Pickup</span>
                            @if(in_array($batch->status, ['pending', 'processing', 'awb_generated', 'labels_generated', 'slips_printed']))
                                <button wire:click="schedulePickup" wire:loading.attr="disabled" wire:target="schedulePickup" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed" @if($isParallel || $batch->locked_by_user_id !== auth()->id() || is_null($batch->slips_printed_at)) disabled @endif>
                                    Schedule Pickup
                                </button>
                            @else
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Pickup Ready
                                </span>
                            @endif
                        </div>

                        <!-- Dispatch Confirm -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Dispatch Confirmation</span>
                            @if(!in_array($batch->status, ['dispatched', 'completed']))
                                <button wire:click="confirmDispatch" wire:loading.attr="disabled" wire:target="confirmDispatch" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed" @if($isParallel || $batch->locked_by_user_id !== auth()->id() || $batch->status !== 'ready_for_pickup') disabled @endif>
                                    Confirm Dispatch
                                </button>
                            @else
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Dispatched
                                </span>
                            @endif
                        </div>

                        <!-- Mark Completed -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Completion Status</span>
                            @if($batch->status !== 'completed')
                                <button wire:click="markCompleted" wire:loading.attr="disabled" wire:target="markCompleted" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 disabled:opacity-50 disabled:cursor-not-allowed" @if($isParallel || $batch->locked_by_user_id !== auth()->id() || $batch->status !== 'dispatched') disabled @endif>
                                    Mark Completed
                                </button>
                            @else
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Completed
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($batch->locked_by_user_id !== auth()->id() && !$isParallel)
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-2">You must acquire the lock to perform operations.</p>
                    @endif
                </div>
            </div>

            <!-- Audit Timeline -->
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Activity Timeline
                    </h3>
                </div>
                <div class="p-4 overflow-y-auto max-h-96">
                    @if($batch->activityLogs && $batch->activityLogs->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($batch->activityLogs->sortByDesc('created_at') as $index => $log)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-slate-700" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center ring-8 ring-white dark:ring-slate-800">
                                                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $log->action }} <span class="font-medium text-gray-900 dark:text-white">by {{ $log->user->name ?? 'System' }}</span></p>
                                                    </div>
                                                    <div class="text-right text-xs whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                        <time datetime="{{ $log->created_at }}">{{ $log->created_at->format('M d, H:i') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                            No activity logs found.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
