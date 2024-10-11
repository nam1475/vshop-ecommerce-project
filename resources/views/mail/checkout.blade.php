<h1>Checkout success</h1>

@php
    function getTotalPrice($price, $quantity)
    {
        return $price * $quantity;
    }
    dd($order);
@endphp

<div class="mt-6 sm:mt-8 lg:flex lg:gap-8">
    <div class="w-full divide-y divide-gray-200 overflow-hidden rounded-lg border border-gray-200 dark:divide-gray-700 lg:max-w-xl xl:max-w-2xl">
        @foreach ($order->orderItems as $item)
            <div class="space-y-4 p-6">
                <div class="flex items-center gap-6">
                    <a href="#" class="h-26 w-20 shrink-0">
                        <img class="h-full w-full dark:hidden" :src="{{ $item->product->images[0] }}" />
                    </a>

                    <a href="{{ route('customer.product.details', $item->product->slug) }}" class="min-w-0 flex-1 font-medium text-gray-900 hover:underline dark:text-white">
                        {{ $item->product->name }}
                    </a>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <p class="text-base font-normal text-gray-900 dark:text-white">x{{ $item->quantity }}</p>
                    <p class="text-xl font-bold leading-tight text-gray-900 dark:text-white">${{ getTotalPrice($item->unit_price, $item->quantity) }}</p>
                </div>
            </div>
        @endforeach

        <div class="space-y-4 bg-gray-50 p-6">
            <div class="space-y-2">
                <dl class="flex items-center justify-between gap-4">
                    <dt class="font-normal text-gray-500 dark:text-gray-400">Original price</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">${{ $order->total_price }}</dd>
                </dl>

                <dl class="flex items-center justify-between gap-4">
                <dt class="font-normal text-gray-500 dark:text-gray-400">Savings</dt>
                <dd class="text-base font-medium text-green-500">-$299.00</dd>
                </dl>

                <dl class="flex items-center justify-between gap-4">
                <dt class="font-normal text-gray-500 dark:text-gray-400">Store Pickup</dt>
                <dd class="font-medium text-gray-900 dark:text-white">$99</dd>
                </dl>

                <dl class="flex items-center justify-between gap-4">
                <dt class="font-normal text-gray-500 dark:text-gray-400">Tax</dt>
                <dd class="font-medium text-gray-900 dark:text-white">$799</dd>
                </dl>
            </div>

            <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2">
                <dt class="text-lg font-bold text-gray-900 dark:text-white">Total</dt>
                <dd class="text-lg font-bold text-gray-900 dark:text-white">${{ $order->total_price }}</dd>
            </dl>
        </div>
    </div>

</div>