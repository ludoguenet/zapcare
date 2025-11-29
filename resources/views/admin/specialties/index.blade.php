@extends('layouts.app')

@section('title', 'Specialties - ZapCare Admin')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-16">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-semibold text-[#0F172A] mb-2">Specialties</h1>
            <p class="text-[#64748B]">Manage medical specialties</p>
        </div>
        <a href="{{ route('admin.specialties.create') }}" class="bg-[#2563EB] hover:bg-[#1E3A8A] text-white px-6 py-3 rounded-xl font-medium shadow-sm transition-colors">
            Add Specialty
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <ul class="divide-y divide-slate-200">
            @foreach($specialties as $specialty)
                <li>
                    <div class="px-6 py-5 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-x-4 flex-1 min-w-0">
                            <div class="flex h-12 w-12 flex-none items-center justify-center rounded-lg bg-[#A7F3D0] ring-1 ring-slate-900/10">
                                <i data-lucide="stethoscope" class="w-6 h-6 text-[#10B981]"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-[#0F172A]">{{ $specialty->name }}</p>
                                @if($specialty->description)
                                    <p class="text-xs text-[#64748B] mt-1">{{ $specialty->description }}</p>
                                @endif
                                <p class="text-xs text-[#64748B] mt-2">
                                    <span class="font-medium text-[#0F172A]">{{ $specialty->doctors_count }}</span> doctor(s)
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4 ml-4">
                            <a href="{{ route('admin.specialties.edit', $specialty) }}" class="text-sm font-medium text-[#2563EB] hover:text-[#1E3A8A] transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.specialties.destroy', $specialty) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-[#EF4444] hover:text-red-700 transition-colors" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-8">
        {{ $specialties->links() }}
    </div>
</div>
@endsection
