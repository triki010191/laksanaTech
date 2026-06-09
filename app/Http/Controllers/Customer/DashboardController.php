<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Project;
use App\Models\SupportTicket;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        return view('customer.dashboard', [
            'stats' => [
                'orders' => Order::query()->where('user_id', $userId)->count(),
                'invoices' => Invoice::query()->where('user_id', $userId)->count(),
                'projects' => Project::query()->where('user_id', $userId)->count(),
                'tickets' => SupportTicket::query()->where('user_id', $userId)->where('status', '!=', 'closed')->count(),
            ],
            'recentOrders' => Order::query()->where('user_id', $userId)->latest()->limit(5)->get(),
        ]);
    }

    public function orders(): View
    {
        return view('customer.orders', [
            'orders' => Order::query()
                ->with('items')
                ->where('user_id', auth()->id())
                ->latest()
                ->paginate(10),
        ]);
    }

    public function invoices(): View
    {
        return view('customer.invoices', [
            'invoices' => Invoice::query()
                ->with('order')
                ->where('user_id', auth()->id())
                ->latest()
                ->paginate(10),
        ]);
    }

    public function projects(): View
    {
        return view('customer.projects', [
            'projects' => Project::query()
                ->with('milestones')
                ->where('user_id', auth()->id())
                ->latest()
                ->paginate(10),
        ]);
    }

    public function tickets(): View
    {
        return view('customer.tickets', [
            'tickets' => SupportTicket::query()
                ->where('user_id', auth()->id())
                ->latest()
                ->paginate(10),
        ]);
    }
}
