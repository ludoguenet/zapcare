@extends('layouts.app')

@section('title', 'Edit Specialty - ZapCare Admin')

@section('content')
<div class="max-w-4xl mx-auto px-6 lg:px-16">
    <div class="mb-8">
        <h1 class="text-4xl font-semibold text-[#0F172A] mb-2">Edit Specialty</h1>
        <p class="text-[#64748B]">Update specialty information</p>
    </div>

    <form method="POST" action="{{ route('admin.specialties.update', $specialty) }}" class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        @csrf
        @method('PUT')
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-[#0F172A]">Specialty Details</h2>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-[#0F172A] mb-2">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $specialty->name) }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-[#0F172A] mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">{{ old('description', $specialty->description) }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-[#2563EB] hover:bg-[#1E3A8A] text-white px-6 py-3 rounded-lg font-medium shadow-sm transition-colors">
                    Update Specialty
                </button>
                <a href="{{ route('admin.specialties.index') }}" class="bg-slate-100 hover:bg-slate-200 text-[#0F172A] px-6 py-3 rounded-lg font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
