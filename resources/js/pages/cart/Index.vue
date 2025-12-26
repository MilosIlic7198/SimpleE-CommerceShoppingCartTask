<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { cartIndex } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, router } from '@inertiajs/vue3';
import PlaceholderPattern from '../../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Cart',
        href: cartIndex().url,
    },
];

type Product = {
    id: number;
    name: string;
    description: string;
    price: string;
    stock_quantity: number;
};

type CartItem = {
    id: number;
    product_id: number;
    product: Product;
    quantity: number;
};

const props = defineProps<{
    cartItems: CartItem[];
}>();

/**
 * Increase quantity (max = stock)
 */
const increase = (item: CartItem) => {
    if (item.quantity < item.product.stock_quantity) {
        item.quantity++;
    }
    router.put(`/cart/${item.id}`, { quantity: item.quantity });
};

/**
 * Decrease quantity (min = 1)
 */
const decrease = (item: CartItem) => {
    if (item.quantity > 1) {
        item.quantity--;
    }
    router.put(`/cart/${item.id}`, { quantity: item.quantity });
};

/**
 * Remove item completely
 */
const remove = (itemId: number) => {
    router.delete(`/cart/${itemId}`)
};

const buyProducts = () => {
  if (props.cartItems.length === 0) return;

  // Map cartItems to the backend format
  const products = props.cartItems.map(item => ({
    id: item.product_id,
    quantity: item.quantity,
  }));

  // Send to backend
  router.put('/products', { products }, {
    onSuccess: () => {
      alert('You bought the products!');
    },
    onError: (error) => {
      console.error('Error:', error);
      alert('Failed!');
    }
  });
}

</script>

<template>
  <Head title="Cart" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

      <!-- Cart Items Grid -->
      <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div
          v-for="item in props.cartItems"
          :key="item.id"
          class="rounded-xl border p-4 flex flex-col"
        >
          <h3 class="text-lg font-bold">{{ item.product.name }}</h3>
          <p class="text-sm text-gray-500">{{ item.product.description }}</p>
          <p class="mt-2 font-semibold">${{ item.product.price }}</p>
          <p class="text-sm text-gray-400 mb-3">Stock: {{ item.product.stock_quantity }}</p>

          <!-- Quantity Controls -->
          <div class="flex items-center justify-between gap-2 mt-4">
            <Button
              type="button"
              variant="outline"
              class="px-3"
              :disabled="item.quantity <= 1"
              @click="decrease(item)"
            >−</Button>

            <span class="w-8 text-center font-semibold">{{ item.quantity }}</span>

            <Button
              type="button"
              variant="outline"
              class="px-3"
              :disabled="item.quantity >= item.product.stock_quantity"
              @click="increase(item)"
            >+</Button>
          </div>

          <!-- Remove Button -->
          <Button
            type="button"
            variant="destructive"
            class="w-full mt-3"
            @click="remove(item.id)"
          >
            Remove
          </Button>
        </div>
      </div>

      <!-- Buy Products Button -->
      <div class="mt-6 flex justify-end">
        <Button
          type="button"
          variant="primary"
          class="px-8 py-4 text-lg"
          @click="buyProducts"
          :disabled="props.cartItems.length === 0"
        >
          Buy Products
        </Button>
      </div>

      <!-- Placeholder Section -->
      <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
        <PlaceholderPattern />
      </div>
    </div>
  </AppLayout>
</template>

