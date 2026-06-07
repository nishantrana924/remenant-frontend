<div class="space-y-6" wire:init="loadKpis">
    <x-slot name="title">Warehouse Dashboard</x-slot>

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Warehouse Operations</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Real-time overview of batch processing and fulfillment.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.warehouse.batches.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-slate-900 transition-colors">
                Manage Batches
            </a>
        </div>
    </div>

    <!-- Main KPI Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <x-warehouse.kpi-card 
            title="Pending Batches" 
            :value="$kpis['pending'] ?? 0" 
            color="gray"
            :is-loading="$isLoading"
            wire:key="kpi-pending"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </x-slot>
        </x-warehouse.kpi-card>

        <x-warehouse.kpi-card 
            title="Processing" 
            :value="$kpis['processing'] ?? 0" 
            color="blue"
            :is-loading="$isLoading"
            wire:key="kpi-processing"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </x-slot>
        </x-warehouse.kpi-card>

        <x-warehouse.kpi-card 
            title="Ready For Pickup" 
            :value="$kpis['ready_for_pickup'] ?? 0" 
            color="orange"
            :is-loading="$isLoading"
            wire:key="kpi-ready"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </x-slot>
        </x-warehouse.kpi-card>

        <x-warehouse.kpi-card 
            title="Dispatched Today" 
            :value="$kpis['dispatched_today'] ?? 0" 
            color="green"
            :is-loading="$isLoading"
            wire:key="kpi-dispatched"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </x-slot>
        </x-warehouse.kpi-card>
    </div>

    <!-- Secondary Widgets -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        
        <!-- Manual Review Alert -->
        @if(!$isLoading && ($kpis['manual_review'] ?? 0) > 0)
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 dark:border-red-500 p-4 rounded-r-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Action Required</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                            <p>There are {{ $kpis['manual_review'] }} orders requiring manual review for missing dimensions or unmappable addresses.</p>
                        </div>
                        <div class="mt-4">
                            <div class="-mx-2 -my-1.5 flex">
                                <a href="{{ route('admin.warehouse.manual-review.index') }}" class="bg-red-100 dark:bg-red-800/50 px-2 py-1.5 rounded-md text-sm font-medium text-red-800 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                    Resolve Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Parallel Mode Monitor -->
        @if(config('warehouse.automation_mode') === 'parallel')
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Parallel Shadow Engine Active
                    </h3>
                    <div class="mt-2 max-w-xl text-sm text-gray-500 dark:text-gray-400">
                        <p>The warehouse engine is running in shadow mode. Batches are being generated but no physical actions (AWB/Labels) will trigger automatically.</p>
                    </div>
                    <div class="mt-5">
                        <a href="{{ route('admin.warehouse.monitoring') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors">
                            View Shadow Monitor
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
