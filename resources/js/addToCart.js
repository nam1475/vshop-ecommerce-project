import { router } from '@inertiajs/vue3'
import { success, error } from '@/alert.js';

export function addToCart(productId, quantity = 1) {
    router.post(route('customer.cart.store', productId), 
    {
      quantity: quantity
    }, 
    {
      onSuccess: (page) => {
        success(page);
      },
      onError: (page) => {
        error(page);
      }
    });
}