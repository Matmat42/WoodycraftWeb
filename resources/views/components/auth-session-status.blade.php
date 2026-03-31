@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-gray-600 dark:text-gray-400']) }}>
        {{ $status }}
    </div>
@endif
