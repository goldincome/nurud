<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarkupRule;
use Illuminate\Http\Request;

class MarkupController extends Controller
{
    public function index()
    {
        $rules = MarkupRule::orderBy('threshold_price', 'desc')->get();
        return view('admin.settings.markups.index', compact('rules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'operator' => 'required|in:>=,<=,>,<,==',
            'threshold_price' => 'required|numeric|min:0',
            'markup_type' => 'required|in:percentage,flat',
            'markup_value' => 'required|numeric|min:0',
        ]);

        MarkupRule::create($data);
        return back()->with('success', 'Markup rule added successfully.');
    }

    // This method is called via AJAX or returns the data for the modal
    public function edit(MarkupRule $markup)
    {
        return response()->json($markup);
    }

    public function update(Request $request, MarkupRule $markup)
    {
        $data = $request->validate([
            'operator' => 'required|in:>=,<=,>,<,==',
            'threshold_price' => 'required|numeric|min:0',
            'markup_type' => 'required|in:percentage,flat',
            'markup_value' => 'required|numeric|min:0',
        ]);

        $markup->update($data);
        return back()->with('success', 'Markup rule updated successfully.');
    }

    public function destroy(MarkupRule $markup)
    {
        $markup->delete();
        return back()->with('success', 'Rule deleted.');
    }

}