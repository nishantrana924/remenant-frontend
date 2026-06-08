<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Monitoring Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Real-time health and throughput metrics.</p>
    </div>

    <!-- Core Pipeline Health -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 {{ $queueBacklog > 1000 ? 'border-red-500' : 'border-blue-500' }}">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Queue Backlog</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($queueBacklog) }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 {{ $failedJobs > 50 ? 'border-red-500' : ($failedJobs > 0 ? 'border-yellow-500' : 'border-green-500') }}">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Failed Jobs</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($failedJobs) }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 {{ $circuitBreakerStatus === 'OPEN' ? 'border-red-500' : 'border-green-500' }}">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Circuit Breaker</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $circuitBreakerStatus }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 {{ $manualReviewCount > 100 ? 'border-red-500' : 'border-yellow-500' }}">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Manual Review</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($manualReviewCount) }}</p>
        </div>
    </div>

    <!-- Error Spikes & Exceptions -->
    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Error Spikes (Today)</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border border-gray-100 dark:border-slate-700">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Validation Failures</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($validationFailures) }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border border-gray-100 dark:border-slate-700">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Lock Timeouts</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($lockTimeouts) }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border border-gray-100 dark:border-slate-700">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Notification Failures</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($notificationFailures) }}</p>
        </div>
    </div>

    <!-- Success Rates -->
    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Logistics Success Rates (Today)</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 {{ $awbSuccessRate < 95 ? 'border-red-500' : 'border-green-500' }}">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">AWB Success</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $awbSuccessRate }}%</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 {{ $pickupSuccessRate < 95 ? 'border-red-500' : 'border-green-500' }}">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Pickup Success</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pickupSuccessRate }}%</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 {{ $dispatchSuccessRate < 95 ? 'border-red-500' : 'border-green-500' }}">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Dispatch Success</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dispatchSuccessRate }}%</p>
        </div>
    </div>
</div>
