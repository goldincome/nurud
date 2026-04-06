@extends('layouts.admin')

@section('title', 'Manage Admins')

@section('content')
    <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center bg-white rounded-t-xl">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Admin Users</h2>
            <p class="text-sm text-slate-500">Manage system administrators.</p>
        </div>
        <a href="{{ route('admin.admins.create') }}"
            class="bg-brand-blue text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Create Admin
        </a>
    </div>

    <div class="bg-white shadow-sm border border-slate-200 border-t-0 rounded-b-xl overflow-hidden">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead
                    class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-widest border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Joined</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($admins as $admin)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $admin->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $admin->email }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-[10px] font-bold 
                                    {{ $admin->type === \App\Enums\CustomerType::SUPERADMIN ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                                    {{ $admin->type === \App\Enums\CustomerType::SUPERADMIN ? 'Super Admin' : 'Admin' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $admin->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.admins.edit', $admin->id) }}"
                                    class="text-slate-400 hover:text-brand-blue transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if ($admin->id !== auth()->id())
                                    <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Are you sure you want to delete this admin user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-500 transition" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users-slash text-4xl text-slate-200 mb-2"></i>
                                    <p>No admin users found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-slate-100">
                {{ $admins->links() }}
            </div>
        </div>
    </div>
@endsection