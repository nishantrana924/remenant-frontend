<div>
    <x-slot name="title">Manual Review Queue</x-slot>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
            Manual Review Queue
            @if($isParallel)
                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">
                    Read Only (Parallel Mode)
                </span>
            @endif
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Resolve flagged orders before they can be processed by the automation engine.</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-md p-4 flex items-center">
            <svg class="h-5 w-5 text-green-400 dark:text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
    @endif

    @error('requeue')
        <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-md p-4 flex items-center">
            <svg class="h-5 w-5 text-red-400 dark:text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ $message }}</p>
        </div>
    @enderror

    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Left Pane: Queue Listing -->
        <div class="w-full lg:w-1/3 flex flex-col space-y-4">
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Pending Review ({{ $orders->total() }})</h3>
                </div>
                
                @if($orders->count() > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-slate-700 max-h-[600px] overflow-y-auto">
                        @foreach($orders as $order)
                            <li>
                                <button wire:click="selectOrder({{ $order->id }})" class="w-full text-left px-4 py-4 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors {{ $selectedOrder && $selectedOrder->id === $order->id ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500' : '' }}">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Order #{{ $order->order_number }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-red-600 dark:text-red-400 font-medium truncate">
                                        {{ $order->manual_review_reason ?? 'Validation Failure' }}
                                    </p>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div class="p-3 border-t border-gray-200 dark:border-slate-700">
                        {{ $orders->links('pagination::tailwind') }}
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p>No orders require manual review.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Pane: Order Detail -->
        <div class="w-full lg:w-2/3">
            @if($selectedOrder)
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Order #{{ $selectedOrder->order_number }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                            Action Required
                        </span>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Issue Highlight -->
                        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-r-md">
                            <h4 class="text-sm font-bold text-red-800 dark:text-red-300">Validation Issue</h4>
                            <p class="text-sm text-red-700 dark:text-red-400 mt-1">{{ $selectedOrder->manual_review_reason ?? 'The order failed automated validation checks and requires manual correction.' }}</p>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Shipping Address -->
                            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Shipping Address</h4>
                                    <button wire:click="openAddressModal({{ $selectedOrder->id }})" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Edit</button>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                    <p>{{ $selectedOrder->shipping_address ?? 'No Address Line' }}</p>
                                    <p>{{ $selectedOrder->shipping_city ?? 'No City' }}, {{ $selectedOrder->shipping_state ?? 'No State' }} {{ $selectedOrder->shipping_pincode ?? '' }}</p>
                                </div>
                            </div>

                            <!-- Package Dimensions -->
                            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Dimensions & Weight</h4>
                                    <button wire:click="openDimensionsModal({{ $selectedOrder->id }})" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Edit</button>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                    <p>Weight: <span class="font-medium text-gray-900 dark:text-white">{{ $selectedOrder->total_weight ?? 'Missing' }} kg</span></p>
                                    <p>Dimensions: {{ $selectedOrder->length ?? '-' }} x {{ $selectedOrder->width ?? '-' }} x {{ $selectedOrder->height ?? '-' }} cm</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Bar -->
                        <div class="mt-8 border-t border-gray-200 dark:border-slate-700 pt-6 flex justify-end">
                            <button wire:click="requeueOrder({{ $selectedOrder->id }})" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50" @if($isParallel) disabled @endif>
                                <svg wire:loading wire:target="requeueOrder" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Resolve & Return to Engine
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="h-full bg-gray-50 dark:bg-slate-800/50 border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-xl flex items-center justify-center p-12">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Select an Order</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Choose an order from the queue to review and fix validation issues.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    @if($showAddressModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 dark:bg-slate-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeAddressModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-slate-700">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">Edit Shipping Address</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line 1</label>
                                <input wire:model="addressForm.address_line1" type="text" class="mt-1 block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-900 dark:text-white">
                                @error('addressForm.address_line1') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                                    <input wire:model="addressForm.city" type="text" class="mt-1 block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                                    <input wire:model="addressForm.state" type="text" class="mt-1 block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-900 dark:text-white">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pincode</label>
                                <input wire:model="addressForm.pincode" type="text" class="mt-1 block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-900 dark:text-white">
                                @error('addressForm.pincode') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-900/50 px-4 py-3 sm:px-6 flex flex-row-reverse space-x-3 space-x-reverse border-t border-gray-200 dark:border-slate-700">
                        <button wire:click="updateAddress" type="button" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">Save Changes</button>
                        <button wire:click="closeAddressModal" type="button" class="inline-flex justify-center rounded-md border border-gray-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showDimensionsModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 dark:bg-slate-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeDimensionsModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-slate-700">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">Edit Dimensions & Weight</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Weight (kg)</label>
                                <input wire:model="dimensionsForm.weight" type="number" step="0.1" class="mt-1 block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-900 dark:text-white">
                                @error('dimensionsForm.weight') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Length (cm)</label>
                                    <input wire:model="dimensionsForm.length" type="number" step="0.1" class="mt-1 block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Width (cm)</label>
                                    <input wire:model="dimensionsForm.width" type="number" step="0.1" class="mt-1 block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Height (cm)</label>
                                    <input wire:model="dimensionsForm.height" type="number" step="0.1" class="mt-1 block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-900 dark:text-white">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-900/50 px-4 py-3 sm:px-6 flex flex-row-reverse space-x-3 space-x-reverse border-t border-gray-200 dark:border-slate-700">
                        <button wire:click="updateDimensions" type="button" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">Save Changes</button>
                        <button wire:click="closeDimensionsModal" type="button" class="inline-flex justify-center rounded-md border border-gray-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
