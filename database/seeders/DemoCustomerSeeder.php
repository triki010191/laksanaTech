<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Enums\UserStatus;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\TemplateCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoCustomerSeeder extends Seeder
{
    public function run(): void
    {
        TemplateCategory::query()->firstOrCreate(
            ['slug' => 'website'],
            ['name' => 'Website', 'sort_order' => 1],
        );

        $customer = User::query()->firstOrCreate(
            ['email' => 'customer@laksanatech.id'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
                'status' => UserStatus::Active,
            ],
        );

        $customer->syncRoles([RoleName::Customer->value]);

        $order = Order::query()->firstOrCreate(
            ['order_number' => 'ORD-DEMO-001'],
            [
                'user_id' => $customer->id,
                'type' => 'service',
                'status' => 'pending',
                'subtotal' => 2500000,
                'total' => 2500000,
            ],
        );

        Invoice::query()->firstOrCreate(
            ['invoice_number' => 'INV-DEMO-001'],
            [
                'order_id' => $order->id,
                'user_id' => $customer->id,
                'status' => 'sent',
                'amount' => 2500000,
                'due_date' => now()->addDays(7),
            ],
        );
    }
}
