<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\Models\Country; // Needed for create/edit forms
use App\Services\BankService;
use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class AdminBankController extends Controller
{
    protected BankService $bankService;

    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $banks = $this->bankService->getAllBanks();
        return view('admin.settings.banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $countries = Country::all();
        return view('admin.settings.banks.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankRequest $request): RedirectResponse
    {
        $this->bankService->createBank($request->validated());
        return redirect()->route('admin.banks.index')->with('success', 'Bank account added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used usually for settings, but if needed:
        $bank = $this->bankService->getBankById($id);
        return view('admin.settings.banks.show', compact('bank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $bank = $this->bankService->getBankById($id);
        $countries = Country::all();
        return view('admin.settings.banks.edit', compact('bank', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBankRequest $request, string $id): RedirectResponse
    {
        $bank = $this->bankService->getBankById($id);
        $this->bankService->updateBank($bank, $request->validated());
        return redirect()->route('admin.banks.index')->with('success', 'Bank account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $bank = $this->bankService->getBankById($id);
        $this->bankService->deleteBank($bank);
        return redirect()->route('admin.banks.index')->with('success', 'Bank account deleted successfully.');
    }
}
