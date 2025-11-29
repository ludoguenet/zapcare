@extends('layouts.app')

@section('title', $doctor->name . ' - Profile - ZapCare')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-16">
    <!-- Doctor Header -->
    <div class="mb-8">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="flex items-center gap-x-4 px-6 py-5">
                <div class="flex h-12 w-12 flex-none items-center justify-center rounded-lg bg-sky-100">
                    <i data-lucide="user-round" class="w-6 h-6 text-sky-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-semibold text-[#0F172A]">{{ $doctor->name }}</h1>
                    <p class="text-sm text-[#64748B] mt-0.5">{{ $doctor->email }}</p>
                </div>
                @if($doctor->specialties->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($doctor->specialties as $specialty)
                            <span class="rounded-md bg-sky-100 px-2.5 py-1 text-xs font-medium text-sky-800">{{ $specialty->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Appointments -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar-check" class="w-5 h-5 text-sky-600"></i>
                    <h2 class="text-lg font-semibold text-[#0F172A]">Appointments</h2>
                    <span class="ml-auto rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-medium text-sky-800">
                        {{ $appointments->count() }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                @if($appointments->count() > 0)
                    <div class="space-y-4">
                        @foreach($appointments as $appointment)
                            @php
                                $metadata = $appointment->metadata ?? [];
                                $patientName = $metadata['patient_name'] ?? 'Unknown Patient';
                                $firstPeriod = $appointment->periods->first();
                            @endphp
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-[#0F172A]">{{ $patientName }}</h3>
                                        @if($firstPeriod)
                                            <p class="text-xs text-[#64748B] mt-1">
                                                {{ \Carbon\Carbon::parse($firstPeriod->date)->format('M d, Y') }}
                                            </p>
                                            <p class="text-xs text-[#64748B]">
                                                {{ \Carbon\Carbon::parse($firstPeriod->start_time)->format('g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($firstPeriod->end_time)->format('g:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-[#A7F3D0]">
                                        <i data-lucide="check" class="w-4 h-4 text-[#10B981]"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="calendar-x" class="w-10 h-10 text-[#64748B] mx-auto mb-2"></i>
                        <p class="text-sm text-[#64748B]">No appointments scheduled</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Blocked Periods -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="ban" class="w-5 h-5 text-[#EF4444]"></i>
                    <h2 class="text-lg font-semibold text-[#0F172A]">Blocked Periods</h2>
                    <span class="ml-auto rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                        {{ $blocked->count() }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                @if($blocked->count() > 0)
                    <div class="space-y-4">
                        @foreach($blocked as $block)
                            @php
                                $firstPeriod = $block->periods->first();
                            @endphp
                            <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-[#0F172A]">{{ $block->name }}</h3>
                                        @if($firstPeriod)
                                            <p class="text-xs text-[#64748B] mt-1">
                                                {{ \Carbon\Carbon::parse($firstPeriod->date)->format('M d, Y') }}
                                            </p>
                                            <p class="text-xs text-[#64748B]">
                                                {{ \Carbon\Carbon::parse($firstPeriod->start_time)->format('g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($firstPeriod->end_time)->format('g:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-red-100">
                                        <i data-lucide="x" class="w-4 h-4 text-red-600"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="calendar-check" class="w-10 h-10 text-[#64748B] mx-auto mb-2"></i>
                        <p class="text-sm text-[#64748B]">No blocked periods</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Buffer Periods -->
    @if($buffer->count() > 0)
    <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <div class="flex items-center gap-2">
                <i data-lucide="clock" class="w-5 h-5 text-[#F59E0B]"></i>
                <h2 class="text-lg font-semibold text-[#0F172A]">Buffer Periods</h2>
                <span class="ml-auto rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">
                    {{ $buffer->count() }}
                </span>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($buffer as $bufferPeriod)
                    @php
                        $firstPeriod = $bufferPeriod->periods->first();
                    @endphp
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-[#0F172A]">{{ $bufferPeriod->name ?? 'Buffer Period' }}</h3>
                                @if($firstPeriod)
                                    <p class="text-xs text-[#64748B] mt-1">
                                        {{ \Carbon\Carbon::parse($firstPeriod->date)->format('M d, Y') }}
                                    </p>
                                    <p class="text-xs text-[#64748B]">
                                        {{ \Carbon\Carbon::parse($firstPeriod->start_time)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($firstPeriod->end_time)->format('g:i A') }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-amber-100">
                                <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Availability / Office Hours -->
    <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <div class="flex items-center gap-2">
                <i data-lucide="calendar" class="w-5 h-5 text-[#10B981]"></i>
                <h2 class="text-lg font-semibold text-[#0F172A]">Office Hours</h2>
                <span class="ml-auto rounded-full bg-[#A7F3D0] px-2.5 py-0.5 text-xs font-medium text-[#065F46]">
                    {{ $availability->count() }} schedule(s)
                </span>
            </div>
        </div>
        <div class="p-6">
            @if($availability->count() > 0)
                <div class="space-y-6">
                    @foreach($availability as $schedule)
                        @php
                            $frequencyConfig = $schedule->frequency_config ?? [];
                            $days = $frequencyConfig['days'] ?? [];
                            $dayNames = [
                                'monday' => 'Monday',
                                'tuesday' => 'Tuesday',
                                'wednesday' => 'Wednesday',
                                'thursday' => 'Thursday',
                                'friday' => 'Friday',
                                'saturday' => 'Saturday',
                                'sunday' => 'Sunday'
                            ];
                        @endphp
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-[#0F172A] mb-2">Days</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($days as $day)
                                        <span class="inline-flex items-center rounded-md bg-sky-100 px-2.5 py-1 text-xs font-medium text-sky-800">
                                            {{ $dayNames[$day] ?? ucfirst($day) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @if($schedule->periods->count() > 0)
                                <div>
                                    <h3 class="text-sm font-medium text-[#0F172A] mb-2">Time Periods</h3>
                                    <div class="space-y-2">
                                        @php
                                            // Get unique time periods (same start/end time regardless of date)
                                            $uniquePeriods = $schedule->periods->unique(function ($period) {
                                                return $period->start_time . '-' . $period->end_time;
                                            });
                                        @endphp
                                        @foreach($uniquePeriods as $period)
                                            <div class="flex items-center gap-2 text-sm text-[#64748B]">
                                                <i data-lucide="clock" class="w-4 h-4"></i>
                                                <span>
                                                    {{ \Carbon\Carbon::parse($period->start_time)->format('g:i A') }} - 
                                                    {{ \Carbon\Carbon::parse($period->end_time)->format('g:i A') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="mt-4 pt-4 border-t border-slate-200">
                                <p class="text-xs text-[#64748B]">
                                    <span class="font-medium">Valid from:</span> 
                                    {{ \Carbon\Carbon::parse($schedule->start_date)->format('M d, Y') }}
                                    @if($schedule->end_date)
                                        - {{ \Carbon\Carbon::parse($schedule->end_date)->format('M d, Y') }}
                                    @else
                                        (ongoing)
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i data-lucide="calendar-x" class="w-10 h-10 text-[#64748B] mx-auto mb-2"></i>
                    <p class="text-sm text-[#64748B]">No office hours configured</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
