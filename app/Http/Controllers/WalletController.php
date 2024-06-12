<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class WalletController extends Controller
{
    public function earning(Request $request)
    {
        // Fetch all orders and payments
        $orders = Order::all();
        $payments = Payment::all();

        // Calculate earnings and costs
        $yearlyEarnings = $this->calculateEarnings($orders, 'year');
        $monthlyEarnings = $this->calculateEarnings($orders, 'month');
        $weeklyEarnings = $this->calculateEarnings($orders, 'week');
        $gatewayEarnings = $this->calculateGatewayEarnings($orders);

        $yearlyCosts = $this->calculateCosts($payments, 'year');
        $monthlyCosts = $this->calculateCosts($payments, 'month');
        $weeklyCosts = $this->calculateCosts($payments, 'week');

        return response()->json([
            'yearly_earnings' => $yearlyEarnings,
            'monthly_earnings' => $monthlyEarnings,
            'weekly_earnings' => $weeklyEarnings,
            'gateway_earnings' => $gatewayEarnings,
            'yearly_costs' => $yearlyCosts,
            'monthly_costs' => $monthlyCosts,
            'weekly_costs' => $weeklyCosts,
            'costs_details' => $payments,
        ]);
    }

    private function calculateEarnings(Collection $orders, $period)
    {
        return $orders->groupBy(function ($order) use ($period) {
            return Carbon::parse($order->created_at)->format($this->getPeriodFormat($period));
        })->map(function ($group) {
            return $group->sum('amount');
        });
    }

    private function calculateGatewayEarnings(Collection $orders)
    {
        return $orders->groupBy('gateway_name')->map(function ($gatewayGroup) {
            return $gatewayGroup->sum('amount');
        });
    }

    private function calculateCosts(Collection $payments, $period)
    {
        return $payments->groupBy(function ($payment) use ($period) {
            return Carbon::parse($payment->payment_date)->format($this->getPeriodFormat($period));
        })->map(function ($group) {
            return $group->sum('amount');
        });
    }

    private function getPeriodFormat($period)
    {
        switch ($period) {
            case 'year':
                return 'Y';
            case 'month':
                return 'Y-m';
            case 'week':
                return 'o-W'; // ISO-8601 week number of year
            default:
                return 'Y-m-d';
        }
    }
}
