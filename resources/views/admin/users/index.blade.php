@extends('layouts.app')

@section('title', 'Users Management - ZapCare Admin')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-16">
    <div class="mb-8">
        <h1 class="text-4xl font-semibold text-[#0F172A] mb-2">Users Management</h1>
        <p class="text-[#64748B]">Manage doctors and patients</p>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <ul class="divide-y divide-slate-200">
            @foreach($users as $user)
                <li>
                    <div class="px-6 py-5 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-x-4 flex-1 min-w-0">
                            <div class="flex h-12 w-12 flex-none items-center justify-center rounded-lg bg-[#DBEAFE] ring-1 ring-slate-900/10">
                                <i data-lucide="user-round" class="w-6 h-6 text-[#2563EB]"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-x-3">
                                    <p class="text-sm font-medium text-[#0F172A] truncate">{{ $user->name }}</p>
                                    @if($user->is_doctor)
                                        <span class="inline-flex items-center rounded-md bg-[#DBEAFE] px-2 py-1 text-xs font-medium text-[#1E3A8A] ring-1 ring-inset ring-[#2563EB]/20">
                                            Doctor
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-900/10">
                                            Patient
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-[#64748B] mt-1 truncate">{{ $user->email }}</p>
                                @if($user->specialties->count() > 0)
                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        @foreach($user->specialties as $specialty)
                                            <span class="inline-flex items-center rounded-md bg-[#A7F3D0] px-2 py-1 text-xs font-medium text-[#065F46] ring-1 ring-inset ring-[#10B981]/20">
                                                {{ $specialty->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.users.edit', $user) }}" class="ml-4 text-sm font-medium text-[#2563EB] hover:text-[#1E3A8A] transition-colors">
                            Edit
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-8">
        {{ $users->links() }}
    </div>
</div>
@endsection
