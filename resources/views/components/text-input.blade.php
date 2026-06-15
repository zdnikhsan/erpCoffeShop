@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-latte focus:ring-latte rounded-xl shadow-sm text-charcoal']) }}>
