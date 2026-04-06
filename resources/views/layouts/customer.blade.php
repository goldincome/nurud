@extends('layouts.front')

@section('title', 'Customer Panel')

@section('content')
    <div class="min-h-screen bg-slate-50/50 py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar -->
                <aside class="w-full lg:w-72 flex-shrink-0">
                    @include('customer.partials.sidebar')
                </aside>

                <!-- Main Content Area -->
                <div class="flex-1">
                    @if(session('success'))
                        <div
                            class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                            <i class="fas fa-check-circle mr-3"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div
                            class="mb-6 bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                            <i class="fas fa-exclamation-circle mr-3"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('customer_content')
                </div>
            </div>
        </div>
    </div>
@endsection