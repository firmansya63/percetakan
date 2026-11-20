<div {{ $attributes->merge(['class' => 'flex items-center gap-4']) }}>
    <x-application-logo class="h-16 w-16 shrink-0 object-contain" />

    <div class="min-w-0">
        <p class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight">
            {{ $companyProfile->name ?? config('app.name') }}
        </p>

        @if ($companyProfile?->address)
            <p class="text-xs text-gray-600 dark:text-gray-400 leading-snug">{{ $companyProfile->address }}</p>
        @endif

        <p class="text-xs text-gray-600 dark:text-gray-400">
            @if ($companyProfile?->phone)
                <span>Telp/WA: {{ $companyProfile->phone }}</span>
            @endif
            @if ($companyProfile?->phone && $companyProfile?->email)
                <span class="mx-1">&middot;</span>
            @endif
            @if ($companyProfile?->email)
                <span>{{ $companyProfile->email }}</span>
            @endif
        </p>
    </div>
</div>
