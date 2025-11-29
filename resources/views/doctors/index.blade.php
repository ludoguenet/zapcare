@extends('layouts.app')

@section('title', 'Our Doctors - ZapCare')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-16">
    <div class="mb-8">
        <h1 class="text-4xl font-semibold text-[#0F172A] mb-2">Our Doctors</h1>
        <p class="text-[#64748B]">Choose an available time slot below.</p>
    </div>

    <!-- Filter by Specialty -->
    <div class="mb-8">
        <form method="GET" action="{{ route('doctors.index') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label for="specialty_id" class="block text-sm font-medium text-[#0F172A] mb-2">Filter by Specialty</label>
                <div class="relative">
                    <select name="specialty_id" id="specialty_id" class="w-full px-4 py-3 pr-10 bg-white border border-slate-300 rounded-lg shadow-sm focus:border-sky-600 focus:ring-2 focus:ring-sky-600 focus:ring-opacity-20 text-[#0F172A] text-sm font-medium appearance-none cursor-pointer transition-colors hover:border-sky-600">
                        <option value="">All Specialties</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-5 h-5 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
            </div>
            <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-sky-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 dark:bg-sky-500 dark:shadow-none dark:hover:bg-sky-400 dark:focus-visible:outline-sky-500">
                Filter
            </button>
        </form>
    </div>

    <!-- Doctors Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($doctors as $doctor)
            <a href="{{ route('doctors.show', $doctor) }}" class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-sky-600 hover:shadow-md transition-all">
                <div class="flex items-center gap-x-4 border-b border-slate-200 bg-slate-50 p-6">
                    <div class="flex h-12 w-12 flex-none items-center justify-center rounded-lg bg-sky-100 ring-1 ring-slate-900/10">
                        <i data-lucide="user-round" class="w-6 h-6 text-sky-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-[#0F172A] truncate">{{ $doctor->name }}</div>
                        <div class="text-xs text-[#64748B] truncate">{{ $doctor->email }}</div>
                    </div>
                </div>
                <dl class="-my-3 divide-y divide-slate-200 px-6 py-4 text-sm">
                    @if($doctor->specialties->count() > 0)
                        <div class="flex justify-between gap-x-4 py-3">
                            <dt class="text-[#64748B]">Specialties</dt>
                            <dd class="flex flex-wrap gap-1.5 justify-end">
                                @foreach($doctor->specialties->take(2) as $specialty)
                                    <span class="rounded-md bg-sky-100 px-2 py-1 text-xs font-medium text-sky-800">{{ $specialty->name }}</span>
                                @endforeach
                                @if($doctor->specialties->count() > 2)
                                    <span class="text-[#64748B]">+{{ $doctor->specialties->count() - 2 }}</span>
                                @endif
                            </dd>
                        </div>
                    @endif
                    <div class="flex justify-between gap-x-4 py-3">
                        <dt class="text-[#64748B]">Status</dt>
                        <dd class="font-medium text-[#0F172A]">Available</dd>
                    </div>
                </dl>
                <span aria-hidden="true" class="pointer-events-none absolute top-6 right-6 text-slate-300 group-hover:text-sky-600 transition-colors">
                    <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                </span>
            </a>
        @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-[#64748B] text-lg">No doctors found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $doctors->links() }}
    </div>
</div>
@endsection
