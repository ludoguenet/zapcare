@extends('layouts.app')

@section('title', 'Appointment Confirmed - ZapCare')

@section('content')
<div class="max-w-2xl mx-auto px-6 lg:px-16">
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white text-center">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-8">
            <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-lg bg-[#A7F3D0] ring-1 ring-slate-900/10">
                <i data-lucide="check-circle" class="w-10 h-10 text-[#10B981]"></i>
            </div>
        </div>
        <div class="p-8">
            <h1 class="text-2xl font-semibold text-[#0F172A] mb-3">Your appointment is confirmed.</h1>
            <p class="text-sm text-[#64748B] mb-8">Your appointment has been successfully booked.</p>
            <a href="{{ route('home') }}" class="inline-block bg-[#2563EB] hover:bg-[#1E3A8A] text-white px-8 py-3 rounded-lg font-medium shadow-sm transition-colors">
                Return Home
            </a>
        </div>
    </div>
</div>
@endsection
