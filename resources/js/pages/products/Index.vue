<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { productsIndex, storeCart } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: productsIndex().url,
    },
];

const props = defineProps<{
    products: {
        id: number;
        name: string;
        description: string;
        price: string;
        stock_quantity: number;
    }[],
    cartProducts: number[]
}>();

const isProductInCart = (productId: number) => {
return props.cartProducts.includes(productId);
};
</script>

<template>
    <Head title="Products" />

        <AppLayout :breadcrumbs="breadcrumbs">
        <div
                class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
                >
                <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div
                            v-for="product in props.products"
                            :key="product.id"
                            class="rounded-xl border p-4"
                            >
                            <h3 class="text-lg font-bold">{{ product.name }}</h3>
                            <p class="text-sm text-gray-500">{{ product.description }}</p>
                            <p class="mt-2 font-semibold">${{ product.price }}</p>
                            <p class="text-sm text-gray-400 mb-3">
                            Stock: {{ product.stock_quantity }}
                            </p>

                            <!-- Store to cart form -->
                            <Form
                                    v-bind="storeCart.form(product.id)"
                                    >
                                    <Button
                                            type="submit"
                                            class="w-full"
                                            :disabled="isProductInCart(product.id)"
                                            >
                                            Add to Cart
                                    </Button>
                            </Form>                    </div>
                </div>


                <div
                        class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
                        >
                        <PlaceholderPattern />
                </div>
        </div>
        </AppLayout>
</template>

