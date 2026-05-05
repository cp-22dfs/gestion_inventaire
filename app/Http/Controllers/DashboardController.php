<?php

namespace App\Http\Controllers;

use App\Models\Item;

class DashboardController extends Controller
{
    private function getDashboardData()
    {
        return Item::withExists([
            'loans' => function ($query) {
                $query->whereNull('end_date');
            }
        ])->get();
    }

    public function admin()
    {
        $items = $this->getDashboardData();
        return view('admin.dashboard', compact('items'));
    }

    public function user()
    {
        $items = $this->getDashboardData();
        return view('user.dashboard', compact('items'));
    }
}