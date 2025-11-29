@extends('layouts.app')

@section('title', 'ZapCare - Fast, Simple, Reliable Care Scheduling')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-16">
    <!-- Hero Section -->
    <div class="text-center py-20">
        <div class="mb-8">
            <img src="{{ asset('logo.png') }}" alt="ZapCare" class="mx-auto h-24 w-auto">
        </div>
        <h1 class="text-5xl font-semibold text-[#0F172A] mb-4">Book Care in a Flash</h1>
        <p class="text-lg text-[#64748B] mb-10 max-w-2xl mx-auto">Modern scheduling for modern clinics. Fast, simple, reliable care scheduling.</p>
        <a href="{{ route('doctors.index') }}" class="inline-flex items-center gap-x-2 rounded-md bg-sky-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-sky-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 dark:bg-sky-500 dark:shadow-none dark:hover:bg-sky-400 dark:focus-visible:outline-sky-500">
            Browse Doctors
        </a>
    </div>

    <!-- Specialties Section -->
    <div class="mt-20">
        <h2 class="text-2xl font-semibold text-[#0F172A] mb-10 text-center">Our Specialties</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @forelse(\App\Models\Specialty::all() as $specialty)
                <a href="{{ route('doctors.index', ['specialty_id' => $specialty->id]) }}" class="group relative rounded-xl border border-slate-200 bg-white p-6 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-sky-600 hover:shadow-sm hover:border-sky-600 transition-all">
                    <div>
                        @php
                            $iconName = strtolower($specialty->name);
                            $lucideIcon = 'stethoscope';
                            
                            if (str_contains($iconName, 'neuro')) {
                                $lucideIcon = 'brain';
                            } elseif (str_contains($iconName, 'psychiatr') || str_contains($iconName, 'psychology')) {
                                $lucideIcon = 'brain-circuit';
                            } elseif (str_contains($iconName, 'cardio')) {
                                $lucideIcon = 'heart-pulse';
                            } elseif (str_contains($iconName, 'ortho')) {
                                $lucideIcon = 'bone';
                            } elseif (str_contains($iconName, 'pediatr')) {
                                $lucideIcon = 'baby';
                            } elseif (str_contains($iconName, 'ophthal') || str_contains($iconName, 'eye')) {
                                $lucideIcon = 'eye';
                            } elseif (str_contains($iconName, 'ent') || str_contains($iconName, 'ear')) {
                                $lucideIcon = 'ear';
                            } elseif (str_contains($iconName, 'dentist') || str_contains($iconName, 'dental')) {
                                $lucideIcon = 'tooth';
                            } elseif (str_contains($iconName, 'general') || str_contains($iconName, 'family') || str_contains($iconName, 'practice')) {
                                $lucideIcon = 'stethoscope';
                            } elseif (str_contains($iconName, 'immuno') || str_contains($iconName, 'vaccin')) {
                                $lucideIcon = 'syringe';
                            } elseif (str_contains($iconName, 'hemato')) {
                                $lucideIcon = 'droplet';
                            } elseif (str_contains($iconName, 'laboratory') || str_contains($iconName, 'lab') || str_contains($iconName, 'diagnostic')) {
                                $lucideIcon = 'test-tube';
                            } elseif (str_contains($iconName, 'pulmono') || str_contains($iconName, 'lung')) {
                                $lucideIcon = 'lungs';
                            } elseif (str_contains($iconName, 'infectious') || str_contains($iconName, 'infection')) {
                                $lucideIcon = 'virus';
                            } elseif (str_contains($iconName, 'genetic')) {
                                $lucideIcon = 'dna';
                            } elseif (str_contains($iconName, 'gastro') || str_contains($iconName, 'digestive')) {
                                $lucideIcon = 'utensils-crossed';
                            } elseif (str_contains($iconName, 'physio') || str_contains($iconName, 'kinesio')) {
                                $lucideIcon = 'accessibility';
                            } elseif (str_contains($iconName, 'gyneco') || str_contains($iconName, 'obstetr')) {
                                $lucideIcon = 'venus';
                            } elseif (str_contains($iconName, 'radio') || str_contains($iconName, 'imaging')) {
                                $lucideIcon = 'scan';
                            } elseif (str_contains($iconName, 'pharmacy') || str_contains($iconName, 'pharmac')) {
                                $lucideIcon = 'pill';
                            } elseif (str_contains($iconName, 'dermato')) {
                                $lucideIcon = 'smile';
                            } elseif (str_contains($iconName, 'internal')) {
                                $lucideIcon = 'briefcase-medical';
                            }
                        @endphp
                        <span class="inline-flex rounded-lg bg-sky-100 p-3 text-sky-600">
                            <i data-lucide="{{ $lucideIcon }}" class="w-6 h-6"></i>
                        </span>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-base font-semibold text-[#0F172A]">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            {{ $specialty->name }}
                        </h3>
                        @if($specialty->description)
                            <p class="mt-2 text-sm text-[#64748B]">{{ $specialty->description }}</p>
                        @endif
                    </div>
                    <span aria-hidden="true" class="pointer-events-none absolute top-6 right-6 text-slate-300 group-hover:text-sky-600 transition-colors">
                        <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                    </span>
                </a>
            @empty
                <p class="text-[#64748B] col-span-3 text-center py-8">No specialties available yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
