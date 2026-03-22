<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PricingSetting;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $pricings = PricingSetting::latest()->paginate(15);
        $currentPrice = PricingSetting::where('is_active', true)->first();
        return view('pricing.index', compact('pricings', 'currentPrice'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'price_per_percentage' => 'required|numeric|min:0.01',
        ]);

        PricingSetting::where('is_active', true)->update(['is_active' => false]);

        PricingSetting::create([
            'price_per_percentage' => $validated['price_per_percentage'],
            'is_active' => true,
        ]);

        return redirect()->route('web.pricing.index')->with('success', 'Pricing updated successfully.');
    }
}
