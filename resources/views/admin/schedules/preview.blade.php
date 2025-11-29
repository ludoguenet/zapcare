@extends('layouts.app')

@section('title', 'Preview Slots - ZapCare Admin')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-16">
    <div class="mb-8">
        <h1 class="text-4xl font-semibold text-[#0F172A] mb-2">Preview Bookable Slots</h1>
        <p class="text-[#64748B]">{{ $doctor->name }}</p>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.schedules.preview', $doctor) }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label for="date" class="block text-sm font-medium text-[#0F172A] mb-2">Select Date</label>
                    <input type="date" id="date" name="date" value="{{ $date }}" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-sky-600 focus:ring-2 focus:ring-sky-600 focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
                </div>
                <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-sky-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 dark:bg-sky-500 dark:shadow-none dark:hover:bg-sky-400 dark:focus-visible:outline-sky-500">
                    Load Slots
                </button>
            </form>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-[#0F172A]">Available Slots for {{ $date }}</h2>
        </div>
        <div class="p-6">
        @if(count($slots) > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($slots as $slot)
                    <div class="p-4 rounded-xl border {{ $slot['is_available'] ? 'bg-[#A7F3D0] border-[#10B981]' : 'bg-red-50 border-red-200' }}">
                        <p class="font-medium text-[#0F172A] mb-1">{{ date('g:i A', strtotime($slot['start_time'])) }} - {{ date('g:i A', strtotime($slot['end_time'])) }}</p>
                        <p class="text-sm font-medium {{ $slot['is_available'] ? 'text-[#065F46]' : 'text-red-700' }}">
                            {{ $slot['is_available'] ? 'Available' : 'Unavailable' }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-[#64748B] text-center py-8">No slots available for this date.</p>
        @endif
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.schedules.show', $doctor) }}" class="inline-flex items-center space-x-2 text-sky-600 hover:text-sky-800 font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Back to Schedule Management</span>
        </a>
    </div>
</div>
@endsection
