@extends('layouts.app')

@section('title', 'Edit User - ZapCare Admin')

@section('content')
<div class="max-w-4xl mx-auto px-6 lg:px-16">
    <div class="mb-8">
        <h1 class="text-4xl font-semibold text-[#0F172A] mb-2">Edit User</h1>
        <p class="text-[#64748B]">Update user information and roles</p>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-[#0F172A]">User Information</h2>
        </div>
        <div class="p-6">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-[#0F172A] mb-2">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
            </div>

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-[#0F172A] mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB] focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
            </div>

            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_doctor" value="1" {{ $user->is_doctor ? 'checked' : '' }}
                        class="rounded border-slate-300 text-[#2563EB] focus:ring-[#2563EB] w-5 h-5">
                    <span class="ml-3 text-sm font-medium text-[#0F172A]">Is Doctor</span>
                </label>
            </div>

            <div class="mb-6" id="specialties-section" style="display: {{ $user->is_doctor ? 'block' : 'none' }}">
                <label class="block text-sm font-medium text-[#0F172A] mb-3">Specialties</label>
                <div class="space-y-3">
                    @foreach($specialties as $specialty)
                        <label class="flex items-center cursor-pointer p-3 rounded-lg hover:bg-slate-50 transition-colors">
                            <input type="checkbox" name="specialties[]" value="{{ $specialty->id }}"
                                {{ $user->specialties->contains($specialty->id) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-[#2563EB] focus:ring-[#2563EB] w-5 h-5">
                            <span class="ml-3 text-sm text-[#0F172A]">{{ $specialty->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-[#2563EB] hover:bg-[#1E3A8A] text-white px-6 py-3 rounded-lg font-medium shadow-sm transition-colors">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-slate-100 hover:bg-slate-200 text-[#0F172A] px-6 py-3 rounded-lg font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<script>
document.querySelector('input[name="is_doctor"]').addEventListener('change', function() {
    document.getElementById('specialties-section').style.display = this.checked ? 'block' : 'none';
});
</script>
@endsection
