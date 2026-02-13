@extends('layouts.admin')

@section('title', 'Markup Settings')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-slate-800 mb-4">Add New Rule</h3>
            <form action="{{ route('admin.markups.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Condition</label>
                    <div class="flex gap-2">
                        <select name="operator" class="w-1/3 border border-slate-200 rounded-lg p-2 text-sm focus:ring-brand-orange">
                            <option value=">=">≥</option>
                            <option value="<=">≤</option>
                            <option value=">">></option>
                            <option value="<"><</option>
                            <option value="==">=</option>
                        </select>
                        <input type="number" name="threshold_price" placeholder="Price" class="w-2/3 border border-slate-200 rounded-lg p-2 text-sm">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Markup Action</label>
                    <div class="flex gap-2">
                        <select name="markup_type" class="w-1/3 border border-slate-200 rounded-lg p-2 text-sm focus:ring-brand-orange">
                            <option value="percentage">%</option>
                            <option value="flat">Flat</option>
                        </select>
                        <input type="number" step="0.01" name="markup_value" placeholder="Value" class="w-2/3 border border-slate-200 rounded-lg p-2 text-sm">
                    </div>
                </div>

                <button type="submit" class="w-full bg-brand-orange text-white font-bold py-2 rounded-lg hover:bg-orange-600 transition-colors">
                    Save Rule
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-widest">
                    <tr>
                        <th class="px-6 py-4">If Price is...</th>
                        <th class="px-6 py-4">Apply Markup</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @foreach($rules as $rule)
                    <tr>
                        <td class="px-6 py-4 font-medium">
                            {{ $rule->operator }} ₦{{ number_format($rule->threshold_price) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $rule->markup_type === 'percentage' ? $rule->markup_value . '%' : '₦' . number_format($rule->markup_value) }}
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-3">
                            <button onclick="openEditModal({{ $rule->id }})" class="text-slate-400 hover:text-brand-blue transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form action="{{ route('admin.markups.destroy', $rule) }}" method="POST" onsubmit="return confirm('Delete this rule?')">
                                @csrf @method('DELETE')
                                <button class="text-red-300 hover:text-red-500"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-800">Edit Markup Rule</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="text-xs font-bold text-slate-500 uppercase">Condition</label>
                <div class="flex gap-2">
                    <select id="edit_operator" name="operator" class="w-1/3 border border-slate-200 rounded-lg p-2 text-sm">
                        <option value=">=">≥</option>
                        <option value="<=">≤</option>
                        <option value=">">></option>
                        <option value="<"><</option>
                        <option value="==">=</option>
                    </select>
                    <input type="number" id="edit_threshold" name="threshold_price" class="w-2/3 border border-slate-200 rounded-lg p-2 text-sm">
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-500 uppercase">Markup Action</label>
                <div class="flex gap-2">
                    <select id="edit_type" name="markup_type" class="w-1/3 border border-slate-200 rounded-lg p-2 text-sm">
                        <option value="percentage">%</option>
                        <option value="flat">Flat</option>
                    </select>
                    <input type="number" step="0.01" id="edit_value" name="markup_value" class="w-2/3 border border-slate-200 rounded-lg p-2 text-sm">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="flex-1 bg-slate-100 text-slate-600 font-bold py-2 rounded-lg text-sm">Cancel</button>
                <button type="submit" class="flex-1 bg-brand-blue text-white font-bold py-2 rounded-lg text-sm">Update Rule</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script>
    function openEditModal(id) {
        // Fetch current rule data
        fetch(`/admin/markups/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                // Populate form fields
                document.getElementById('edit_operator').value = data.operator;
                document.getElementById('edit_threshold').value = data.threshold_price;
                document.getElementById('edit_type').value = data.markup_type;
                document.getElementById('edit_value').value = data.markup_value;
                
                // Set form action URL dynamically
                document.getElementById('editForm').action = `/admin/markups/${id}`;
                
                // Show modal
                document.getElementById('editModal').classList.remove('hidden');
            });
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeEditModal();
    });
</script>
@endsection