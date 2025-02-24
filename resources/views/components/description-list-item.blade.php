@props(['term', 'value'])

<div class="px-2 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
    <dt class="text-gray-700">{{ $term ?? '' }}</dt>
    <dd class="mt-1 font-medium text-gray-900 sm:col-span-2 sm:mt-0">{{ $value ?? ''}}</dd>
</div>
