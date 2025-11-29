@extends('layouts.app')

@section('title', 'Create Specialty - ZapCare Admin')

@section('content')
<div class="max-w-4xl mx-auto px-6 lg:px-16">
    <div class="mb-8">
        <h1 class="text-4xl font-semibold text-[#0F172A] mb-2">Create Specialty</h1>
        <p class="text-[#64748B]">Add a new medical specialty</p>
    </div>

    <form method="POST" action="{{ route('admin.specialties.store') }}" class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        @csrf
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-[#0F172A]">Specialty Details</h2>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-[#0F172A] mb-2">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-sky-600 focus:ring-2 focus:ring-sky-600 focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors"
                    placeholder="e.g., Cardiology">
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-[#0F172A] mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-sky-600 focus:ring-2 focus:ring-sky-600 focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors"
                    placeholder="Brief description of the specialty">{{ old('description') }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-sky-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 dark:bg-sky-500 dark:shadow-none dark:hover:bg-sky-400 dark:focus-visible:outline-sky-500">
                    Create Specialty
                </button>
                <a href="{{ route('admin.specialties.index') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-sky-50 px-3 py-2 text-sm font-semibold text-sky-600 shadow-xs hover:bg-sky-100 dark:bg-sky-500/20 dark:text-sky-400 dark:shadow-none dark:hover:bg-sky-500/30">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
