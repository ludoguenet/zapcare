@extends('layouts.app')

@section('title', 'Specialties - ZapCare Admin')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-16">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-semibold text-[#0F172A] mb-2">Specialties</h1>
            <p class="text-[#64748B]">Manage medical specialties</p>
        </div>
        <a href="{{ route('admin.specialties.create') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-sky-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 dark:bg-sky-500 dark:shadow-none dark:hover:bg-sky-400 dark:focus-visible:outline-sky-500">
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
                            <a href="{{ route('admin.specialties.edit', $specialty) }}" class="rounded-sm bg-sky-50 px-2 py-1 text-sm font-semibold text-sky-600 shadow-xs hover:bg-sky-100 dark:bg-sky-500/20 dark:text-sky-400 dark:shadow-none dark:hover:bg-sky-500/30">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.specialties.destroy', $specialty) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-sm bg-red-50 px-2 py-1 text-sm font-semibold text-red-600 shadow-xs hover:bg-red-100 dark:bg-red-500/20 dark:text-red-400 dark:shadow-none dark:hover:bg-red-500/30" onclick="return confirm('Are you sure?')">
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
