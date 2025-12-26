<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        Product::create(['name' => 'Laptop', 'description' => 'High-end laptop', 'price' => 1299.99, 'stock_quantity' => 10]);
        Product::create(['name' => 'Mouse', 'description' => 'Wireless mouse', 'price' => 29.99, 'stock_quantity' => 50]);
        Product::create(['name' => 'Keyboard', 'description' => 'Mechanical keyboard', 'price' => 89.99, 'stock_quantity' => 3]);
        Product::create(['name' => 'Monitor', 'description' => '27-inch 4K display', 'price' => 399.99, 'stock_quantity' => 15]);
        Product::create(['name' => 'Headphones', 'description' => 'Noise-cancelling headphones', 'price' => 199.99, 'stock_quantity' => 8]);
        Product::create(['name' => 'Webcam', 'description' => 'HD webcam for streaming', 'price' => 59.99, 'stock_quantity' => 25]);
        Product::create(['name' => 'Chair', 'description' => 'Ergonomic office chair', 'price' => 149.99, 'stock_quantity' => 5]);
        Product::create(['name' => 'Desk Lamp', 'description' => 'LED desk lamp with adjustable brightness', 'price' => 39.99, 'stock_quantity' => 20]);
        Product::create(['name' => 'External SSD', 'description' => '1TB portable SSD', 'price' => 129.99, 'stock_quantity' => 12]);
        Product::create(['name' => 'Printer', 'description' => 'All-in-one wireless printer', 'price' => 179.99, 'stock_quantity' => 7]);
        Product::create(['name' => 'Smartphone', 'description' => 'Latest model smartphone', 'price' => 999.99, 'stock_quantity' => 18]);
        Product::create(['name' => 'Tablet', 'description' => '10-inch tablet for work and entertainment', 'price' => 449.99, 'stock_quantity' => 22]);
        Product::create(['name' => 'Router', 'description' => 'High-speed Wi-Fi 6 router', 'price' => 89.99, 'stock_quantity' => 30]);
        Product::create(['name' => 'Speakers', 'description' => 'Bluetooth portable speakers', 'price' => 59.99, 'stock_quantity' => 40]);
        Product::create(['name' => 'Smartwatch', 'description' => 'Fitness smartwatch with heart-rate monitor', 'price' => 199.99, 'stock_quantity' => 15]);
        Product::create(['name' => 'Gaming Console', 'description' => 'Latest generation gaming console', 'price' => 499.99, 'stock_quantity' => 10]);
        Product::create(['name' => 'Camera', 'description' => 'Mirrorless camera with 4K video', 'price' => 799.99, 'stock_quantity' => 6]);
        Product::create(['name' => 'Microphone', 'description' => 'Studio quality USB microphone', 'price' => 129.99, 'stock_quantity' => 14]);
        Product::create(['name' => 'Graphics Card', 'description' => 'High-performance GPU', 'price' => 699.99, 'stock_quantity' => 4]);
        Product::create(['name' => 'Laptop Stand', 'description' => 'Adjustable aluminum laptop stand', 'price' => 49.99, 'stock_quantity' => 25]);
    }

}

