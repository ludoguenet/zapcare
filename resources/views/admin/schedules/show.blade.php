@extends('layouts.app')

@section('title', 'Schedule Management - ZapCare Admin')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-16">
    <div class="mb-8">
        <h1 class="text-4xl font-semibold text-[#0F172A] mb-2">Schedule Management</h1>
        <p class="text-[#64748B]">{{ $doctor->name }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Create Office Hours -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-[#0F172A]">Create Office Hours</h2>
            </div>
            <div class="p-6">
            <form method="POST" action="{{ route('admin.schedules.office-hours', $doctor) }}">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-[#0F172A] mb-3">Days</label>
                    <div class="space-y-2">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            <label class="flex items-center cursor-pointer p-2 rounded-lg hover:bg-slate-50 transition-colors">
                                <input type="checkbox" name="days[]" value="{{ $day }}" 
                                    {{ in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-[#2563EB] focus:ring-[#2563EB] w-5 h-5">
                                <span class="ml-3 text-sm text-[#0F172A] capitalize">{{ $day }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-[#0F172A] mb-2">Morning Start</label>
                        <input type="time" name="morning_start" value="09:00" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0F172A] mb-2">Morning End</label>
                        <input type="time" name="morning_end" value="12:00" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0F172A] mb-2">Afternoon Start</label>
                        <input type="time" name="afternoon_start" value="14:00" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0F172A] mb-2">Afternoon End</label>
                        <input type="time" name="afternoon_end" value="17:00" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
                    </div>
                </div>
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1E3A8A] text-white px-4 py-3 rounded-lg font-medium shadow-sm transition-colors">
                    Create Office Hours
                </button>
            </form>
            </div>
        </div>

        <!-- Create Blocked Period -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-[#0F172A]">Block Time Period</h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.schedules.blocked', $doctor) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-[#0F172A] mb-2">Name</label>
                        <input type="text" name="name" placeholder="e.g., Lunch Break" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-[#0F172A] mb-2">Date</label>
                        <input type="date" name="date" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-[#0F172A] mb-2">Start Time</label>
                            <input type="time" name="start_time" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#0F172A] mb-2">End Time</label>
                            <input type="time" name="end_time" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#EF4444] hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium shadow-sm transition-colors">
                        Block Period
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Existing Schedules -->
    <div class="mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-[#0F172A]">Existing Schedules</h2>
        </div>
        <div class="p-6">
        @if($schedules->count() > 0)
            <div class="space-y-4">
                @foreach($schedules as $schedule)
                    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white hover:shadow-sm transition-all">
                        <div class="px-4 py-4 flex items-center justify-between border-b border-slate-200 bg-slate-50">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-[#0F172A] truncate">{{ $schedule->name }}</h3>
                                <p class="text-xs text-[#64748B] mt-0.5">{{ $schedule->schedule_type->value }}</p>
                            </div>
                            <span class="ml-4 inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $schedule->is_active ? 'bg-[#A7F3D0] text-[#065F46] ring-[#10B981]/20' : 'bg-slate-100 text-slate-700 ring-slate-900/10' }}">
                                {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <dl class="-my-3 divide-y divide-slate-200 px-4 py-4 text-sm">
                            <div class="flex justify-between gap-x-4 py-3">
                                <dt class="text-[#64748B]">Date Range</dt>
                                <dd class="text-[#0F172A]">
                                    {{ $schedule->start_date }} 
                                    @if($schedule->end_date)
                                        - {{ $schedule->end_date }}
                                    @endif
                                </dd>
                            </div>
                            @if($schedule->is_recurring)
                                <div class="flex justify-between gap-x-4 py-3">
                                    <dt class="text-[#64748B]">Frequency</dt>
                                    <dd class="text-[#0F172A]">{{ $schedule->frequency }}</dd>
                                </div>
                            @endif
                            @if($schedule->periods->count() > 0)
                                <div class="py-3">
                                    <dt class="text-[#64748B] mb-2">Periods</dt>
                                    <dd class="flex flex-wrap gap-1.5 mt-2">
                                        @foreach($schedule->periods as $period)
                                            <span class="inline-flex items-center rounded-md bg-[#DBEAFE] px-2 py-1 text-xs font-medium text-[#1E3A8A] ring-1 ring-inset ring-[#2563EB]/20">
                                                {{ $period->start_time }} - {{ $period->end_time }}
                                            </span>
                                        @endforeach
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-[#64748B] text-center py-8">No schedules created yet.</p>
        @endif
        </div>
    </div>

    <!-- Preview Slots -->
    <div class="mt-6">
        <a href="{{ route('admin.schedules.preview', $doctor) }}" class="inline-flex items-center space-x-2 text-[#2563EB] hover:text-[#1E3A8A] font-medium transition-colors">
            <span>Preview Bookable Slots</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
</div>
@endsection
