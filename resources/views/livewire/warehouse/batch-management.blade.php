<div>
    <x-slot name="title">Batch Management</x-slot>

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                Batch Management
                @if($isParallel)
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">
                        Read Only (Parallel Mode)
                    </span>
                @endif
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View and manage warehouse fulfillment batches.</p>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 mb-6 space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
        
        <!-- Search Input -->
        <div class="flex-1">
            <label for="search" class="sr-only">Search</label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.500ms="search" type="text" id="search" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md" placeholder="Search by Signature or ID...">
            </div>
        </div>

        <!-- Filter: Status -->
        <div class="w-full sm:w-48">
            <select wire:model.live="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="awb_generated">AWB Generated</option>
                <option value="labels_generated">Labels Generated</option>
                <option value="ready_for_pickup">Ready For Pickup</option>
                <option value="dispatched">Dispatched</option>
                <option value="frozen">Frozen</option>
            </select>
        </div>

        <!-- Filter: Type -->
        <div class="w-full sm:w-48">
            <select wire:model.live="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="">All Types</option>
                <option value="single">Single Item</option>
                <option value="combo">Combo/Multi</option>
            </select>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading class="flex items-center justify-center p-2">
            <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>
    </div>

    <!-- Data Table -->
    @if($batches->count() > 0)
        <x-warehouse.table>
            <x-slot name="header">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Signature</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metrics</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Couriers (S/A)</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                </tr>
            </x-slot>
            
            @foreach($batches as $batch)
                <tr wire:key="batch-{{ $batch->id }}" class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        <a href="{{ route('admin.warehouse.batches.detail', $batch->id) }}">
                            #{{ $batch->id }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $batch->batch_signature ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <span class="capitalize">{{ $batch->batch_type ?? 'Standard' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <x-warehouse.status-badge :status="$batch->status" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $batch->total_orders }} Orders<br>
                        <span class="text-xs text-gray-400">{{ $batch->total_units }} Units</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        S: {{ $batch->suggestedCourier->name ?? 'None' }}<br>
                        A: {{ $batch->assignedCourier->name ?? 'Pending' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $batch->created_at->format('M d, Y H:i') }}
                    </td>
                </tr>
            @endforeach
        </x-warehouse.table>
        
        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $batches->links() }}
        </div>
    @else
        <x-warehouse.empty-state 
            title="No Batches Found" 
            description="Adjust your search filters or wait for the automation engine to process new orders."
        />
    @endif
</div>
