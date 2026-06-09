<?php

namespace App\Livewire\Admin;

use App\Services\Staff\StaffDashboardService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render(StaffDashboardService $dashboards)
    {
        $user = auth()->user()->load('employeeProfile');

        return view($dashboards->resolveView($user), $dashboards->dataFor($user));
    }
}
