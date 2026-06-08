<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Audit Explorer</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Deep search across all warehouse activity logs. Enforced Read-Only.</p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Keyword Search</label>
            <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search details..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Batch ID</label>
            <input type="text" wire:model.live.debounce.500ms="batchId" placeholder="Batch ID" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">User ID</label>
            <input type="text" wire:model.live.debounce.500ms="userId" placeholder="User ID" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Action Type</label>
            <select wire:model.live="actionType" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Actions</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}">{{ $action }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Date Range</label>
            <select wire:model.live="dateRange" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">Past 7 Days</option>
            </select>
        </div>
    </div>

    <!-- Timeline Grid -->
    <div class="bg-white dark:bg-slate-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-slate-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gray-50 dark:bg-slate-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            @if($log->batch_id)
                                <a href="{{ route('admin.warehouse.batches.detail', $log->batch_id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                    #{{ $log->batch_id }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $log->user_id ?? 'System' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $log->details }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            No logs found matching filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900">
            {{ $logs->links() }}
        </div>
    </div>
</div>
