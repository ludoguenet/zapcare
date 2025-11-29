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
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-sky-600 focus:ring-2 focus:ring-sky-600 focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
            </div>

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-[#0F172A] mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-sky-600 focus:ring-2 focus:ring-sky-600 focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors">
            </div>

            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_doctor" value="1" {{ $user->is_doctor ? 'checked' : '' }}
                        class="rounded border-slate-300 text-sky-600 focus:ring-sky-600 w-5 h-5">
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
                                class="rounded border-slate-300 text-sky-600 focus:ring-sky-600 w-5 h-5">
                            <span class="ml-3 text-sm text-[#0F172A]">{{ $specialty->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-6" id="schedule-section" style="display: {{ $user->is_doctor ? 'block' : 'none' }}">
                <label class="block text-sm font-medium text-[#0F172A] mb-3">Weekly Schedule</label>
                <p class="text-xs text-[#64748B] mb-4">Select the days and time periods when this doctor is available</p>
                <div class="space-y-3">
                    @php
                        $days = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 
                                 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 
                                 'sunday' => 'Sunday'];
                    @endphp
                    @foreach($days as $dayKey => $dayName)
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
                            <span class="text-sm font-medium text-[#0F172A] min-w-[100px]">{{ $dayName }}</span>
                            <div class="flex items-center gap-6">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="schedule[{{ $dayKey }}][am]" value="1"
                                        {{ isset($scheduleData) && isset($scheduleData[$dayKey]['am']) && $scheduleData[$dayKey]['am'] ? 'checked' : '' }}
                                        class="rounded border-slate-300 text-sky-600 focus:ring-sky-600 w-4 h-4">
                                    <span class="ml-2 text-sm text-[#0F172A]">AM</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="schedule[{{ $dayKey }}][pm]" value="1"
                                        {{ isset($scheduleData) && isset($scheduleData[$dayKey]['pm']) && $scheduleData[$dayKey]['pm'] ? 'checked' : '' }}
                                        class="rounded border-slate-300 text-sky-600 focus:ring-sky-600 w-4 h-4">
                                    <span class="ml-2 text-sm text-[#0F172A]">PM</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-sky-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 dark:bg-sky-500 dark:shadow-none dark:hover:bg-sky-400 dark:focus-visible:outline-sky-500">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-sky-50 px-3 py-2 text-sm font-semibold text-sky-600 shadow-xs hover:bg-sky-100 dark:bg-sky-500/20 dark:text-sky-400 dark:shadow-none dark:hover:bg-sky-500/30">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<script>
document.querySelector('input[name="is_doctor"]').addEventListener('change', function() {
    const isChecked = this.checked;
    document.getElementById('specialties-section').style.display = isChecked ? 'block' : 'none';
    document.getElementById('schedule-section').style.display = isChecked ? 'block' : 'none';
});
</script>
@endsection
