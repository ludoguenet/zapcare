@extends('layouts.app')

@section('title', $doctor->name . ' - ZapCare')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-16 mb-8">
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="px-6 py-6">
            <div class="flex items-start justify-between gap-6">
                <div class="flex items-start gap-4 flex-1 min-w-0">
                    <div class="flex h-14 w-14 flex-none items-center justify-center rounded-lg bg-sky-100">
                        <i data-lucide="user-round" class="w-7 h-7 text-sky-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-semibold text-[#0F172A]">{{ $doctor->name }}</h1>
                        <p class="text-sm text-[#64748B] mt-1">{{ $doctor->email }}</p>
                        @if($doctor->specialties->count() > 0)
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach($doctor->specialties as $specialty)
                                    <span class="inline-flex items-center rounded-md bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-200">
                                        {{ $specialty->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('doctors.profile', $doctor) }}" class="inline-flex items-center gap-x-2 rounded-md bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-sky-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 dark:bg-sky-500 dark:shadow-none dark:hover:bg-sky-400 dark:focus-visible:outline-sky-500">
                        <i data-lucide="user-circle" class="w-4 h-4"></i>
                        View Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-16" x-data="bookingForm()">
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="md:grid md:grid-cols-2 md:divide-x md:divide-slate-200">
            <!-- Calendar -->
            <div class="md:pr-14 p-6">
                <div class="flex items-center mb-8">
                    <h2 class="flex-auto text-sm font-semibold text-[#0F172A]" x-text="formatMonthYear(selectedDate)"></h2>
                    <button type="button" @click="previousMonth()" class="-my-1.5 flex flex-none items-center justify-center p-1.5 text-[#64748B] hover:text-[#0F172A] transition-colors">
                        <span class="sr-only">Previous month</span>
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <button type="button" @click="nextMonth()" class="-my-1.5 -mr-1.5 ml-2 flex flex-none items-center justify-center p-1.5 text-[#64748B] hover:text-[#0F172A] transition-colors">
                        <span class="sr-only">Next month</span>
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="grid grid-cols-7 text-center text-xs font-medium text-[#64748B] mb-2">
                    <div>M</div>
                    <div>T</div>
                    <div>W</div>
                    <div>T</div>
                    <div>F</div>
                    <div>S</div>
                    <div>S</div>
                </div>
                <div class="grid grid-cols-7 text-sm">
                    <template x-for="(day, index) in calendarDays" :key="index">
                        <div :class="index < 7 ? 'py-2' : 'py-2 border-t border-slate-200'">
                            <button 
                                type="button" 
                                @click="selectDate(day.date)"
                                :disabled="day.isPast"
                                :class="[
                                    'mx-auto flex size-8 items-center justify-center rounded-full transition-colors',
                                    day.isPast ? 'text-slate-300 cursor-not-allowed' : '',
                                    day.isSelected ? 'font-semibold text-white bg-sky-600' : '',
                                    !day.isSelected && day.isToday ? 'font-semibold text-sky-600 hover:bg-sky-100' : '',
                                    !day.isSelected && !day.isToday && day.isCurrentMonth && !day.isPast ? 'text-[#0F172A] hover:bg-slate-100' : '',
                                    !day.isCurrentMonth ? 'text-slate-400' : ''
                                ]">
                                <time :datetime="day.date" x-text="day.day"></time>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Available Time Slots -->
            <section class="md:pl-14 p-6">
                <h2 class="text-sm font-semibold text-[#0F172A] mb-6">
                    <time :datetime="selectedDate" x-text="formatDateLong(selectedDate)"></time>
                </h2>
                
                <!-- Loading State -->
                <div x-show="loading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-2 border-sky-600 border-t-transparent mb-3"></div>
                    <p class="text-xs text-[#64748B]">Loading slots...</p>
                </div>

                <!-- Available Slots List -->
                <ol x-show="!loading && availableSlots.length > 0" class="flex flex-col gap-y-2">
                    <template x-for="slot in availableSlots" :key="slot.start_time">
                        <li class="group flex items-center gap-x-3 rounded-lg px-4 py-3 border border-slate-200 hover:border-sky-600 hover:bg-slate-50 transition-all cursor-pointer"
                            @click="selectSlot(slot)"
                            :class="selectedSlot && selectedSlot.start_time === slot.start_time ? 'bg-sky-100 border-sky-600' : ''">
                            <div class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-[#A7F3D0]">
                                <svg class="w-4 h-4 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-auto min-w-0">
                                <p class="text-sm font-medium text-[#0F172A]" x-text="formatTime(slot.start_time) + ' - ' + formatTime(slot.end_time)"></p>
                            </div>
                        </li>
                    </template>
                </ol>

                <!-- Empty State -->
                <div x-show="!loading && availableSlots.length === 0" class="text-center py-12">
                    <i data-lucide="calendar-x" class="w-10 h-10 text-[#64748B] mx-auto mb-2"></i>
                    <p class="text-sm text-[#64748B]">No slots available</p>
                    <p class="text-xs text-[#64748B] mt-1">Try another day</p>
                </div>
            </section>
        </div>
    </div>

    <!-- Booking Form -->
    <div x-show="selectedSlot" x-transition class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="p-6">
            <div class="mb-6 pb-6 border-b border-slate-200">
                <p class="text-xs text-[#64748B] mb-1">Selected appointment</p>
                <p class="text-sm font-semibold text-[#0F172A]" x-text="selectedSlot ? formatDateLong(selectedDate) + ' at ' + formatTime(selectedSlot.start_time) + ' - ' + formatTime(selectedSlot.end_time) : ''"></p>
            </div>
            
            <form method="POST" action="{{ route('appointments.store', $doctor) }}">
                @csrf
                <input type="hidden" name="date" :value="selectedDate">
                <input type="hidden" name="start_time" :value="selectedSlot ? selectedSlot.start_time : ''">
                <input type="hidden" name="end_time" :value="selectedSlot ? selectedSlot.end_time : ''">
                
                <div class="mb-6">
                    <label for="patient_name" class="block text-sm font-medium text-[#0F172A] mb-2">Your Name</label>
                    <input 
                        type="text" 
                        id="patient_name" 
                        name="patient_name" 
                        required
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-sky-600 focus:ring-2 focus:ring-sky-600 focus:ring-opacity-20 text-[#0F172A] text-sm transition-colors"
                        placeholder="Enter your full name"
                    >
                </div>

                <button 
                    type="submit" 
                    class="inline-flex items-center justify-center gap-x-1.5 w-full rounded-md bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-sky-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 dark:bg-sky-500 dark:shadow-none dark:hover:bg-sky-400 dark:focus-visible:outline-sky-500"
                >
                    Confirm Booking
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function bookingForm() {
    return {
        selectedDate: '{{ request('date', now()->format('Y-m-d')) }}',
        minDate: new Date().toISOString().split('T')[0],
        slots: [],
        loading: false,
        selectedSlot: null,
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),

        get availableSlots() {
            return this.slots.filter(slot => slot.is_available);
        },

        get calendarDays() {
            // Parse selected date properly to avoid timezone issues
            const [year, month, day] = this.selectedDate.split('-').map(Number);
            const firstDay = new Date(year, month - 1, 1);
            const lastDay = new Date(year, month, 0);
            const daysInMonth = lastDay.getDate();
            const startingDayOfWeek = firstDay.getDay();
            
            // Adjust Monday as first day (0 = Monday, 6 = Sunday)
            const adjustedStartingDay = startingDayOfWeek === 0 ? 6 : startingDayOfWeek - 1;
            
            const days = [];
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
            
            // Previous month days
            const prevMonth = new Date(year, month - 1, 0);
            const prevMonthDays = prevMonth.getDate();
            for (let i = adjustedStartingDay - 1; i >= 0; i--) {
                const dayNum = prevMonthDays - i;
                const dateStr = `${year}-${String(month - 1).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
                const date = new Date(year, month - 2, dayNum);
                date.setHours(0, 0, 0, 0);
                
                days.push({
                    day: dayNum,
                    date: dateStr,
                    isCurrentMonth: false,
                    isToday: false,
                    isSelected: false,
                    isPast: date < today
                });
            }
            
            // Current month days
            for (let dayNum = 1; dayNum <= daysInMonth; dayNum++) {
                const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
                const date = new Date(year, month - 1, dayNum);
                date.setHours(0, 0, 0, 0);
                const isToday = dateStr === todayStr;
                const isSelected = dateStr === this.selectedDate;
                
                days.push({
                    day: dayNum,
                    date: dateStr,
                    isCurrentMonth: true,
                    isToday: isToday,
                    isSelected: isSelected,
                    isPast: date < today
                });
            }
            
            // Next month days to fill the grid
            const totalCells = 42; // 6 weeks * 7 days
            const remainingDays = totalCells - days.length;
            for (let dayNum = 1; dayNum <= remainingDays; dayNum++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
                const date = new Date(year, month, dayNum);
                date.setHours(0, 0, 0, 0);
                
                days.push({
                    day: dayNum,
                    date: dateStr,
                    isCurrentMonth: false,
                    isToday: false,
                    isSelected: false,
                    isPast: date < today
                });
            }
            
            return days;
        },

        init() {
            if (this.selectedDate) {
                this.loadSlots();
            }
        },

        selectDate(date) {
            if (new Date(date) < new Date(this.minDate)) return;
            this.selectedDate = date;
            this.loadSlots();
        },

        previousMonth() {
            const [year, month] = this.selectedDate.split('-').map(Number);
            const date = new Date(year, month - 2, 1);
            this.selectedDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-01`;
        },

        nextMonth() {
            const [year, month] = this.selectedDate.split('-').map(Number);
            const date = new Date(year, month, 1);
            this.selectedDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-01`;
        },

        async loadSlots() {
            if (!this.selectedDate) return;
            
            this.loading = true;
            this.slots = [];
            this.selectedSlot = null;

            try {
                const response = await fetch(`{{ route('doctors.slots', $doctor) }}?date=${this.selectedDate}`);
                const data = await response.json();
                this.slots = data.slots || [];
                
                // Re-initialize Lucide icons after slots are loaded
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            } catch (error) {
                console.error('Error loading slots:', error);
            } finally {
                this.loading = false;
            }
        },

        selectSlot(slot) {
            if (slot.is_available) {
                this.selectedSlot = slot;
            }
        },

        formatTime(time) {
            return new Date('2000-01-01T' + time).toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
        },

        formatDateLong(dateStr) {
            // Parse date string properly to avoid timezone issues
            const [year, month, day] = dateStr.split('-').map(Number);
            const date = new Date(year, month - 1, day);
            return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
        },

        formatMonthYear(dateStr) {
            // Parse date string properly to avoid timezone issues
            const [year, month] = dateStr.split('-').map(Number);
            const date = new Date(year, month - 1, 1);
            return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        }
    }
}
</script>
@endsection
