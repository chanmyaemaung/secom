<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Product Header Section --}}
        <div class="p-6 rounded-lg bg-white dark:bg-gray-800 shadow-sm">
            <div class="flex flex-col gap-4">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $record->name }}
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ $record->description }}
                </p>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-medium text-gray-700 dark:text-gray-300">Price:</span>
                    <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                        ${{ number_format($record->price, 2) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Product Details Section --}}
        <div class="p-6 rounded-lg bg-white dark:bg-gray-800 shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Product Details
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex flex-col gap-1">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $record->status }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Active Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                        {{ $record->is_active
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200'
                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                        }}">
                        {{ $record->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
