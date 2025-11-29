<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ZapCare - Fast, Simple, Reliable Care Scheduling')</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAFC]">
    <nav class="bg-white/80 backdrop-blur border-b border-slate-200 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-8">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('navbar-logo.png') }}" alt="ZapCare" class="h-8 w-auto">
                    </a>
                </div>
                <div class="hidden sm:flex sm:space-x-6">
                    <a href="{{ route('home') }}" class="text-[#64748B] hover:text-sky-600 inline-flex items-center px-3 py-2 text-sm font-medium transition-colors">
                        Home
                    </a>
                    <a href="{{ route('doctors.index') }}" class="text-[#64748B] hover:text-sky-600 inline-flex items-center px-3 py-2 text-sm font-medium transition-colors">
                        Doctors
                    </a>
                </div>
            </div>
            <div class="flex items-center">
                <a href="{{ route('admin.users.index') }}" class="text-[#64748B] hover:text-sky-600 px-3 py-2 text-sm font-medium transition-colors">
                    Admin
                </a>
            </div>
        </div>
    </nav>

    <main class="py-12">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-6 lg:px-16 mb-6">
                <div class="rounded-md bg-green-50 p-4 dark:bg-green-500/10 dark:outline dark:outline-green-500/20">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 text-green-400">
                                <path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Success</h3>
                            <div class="mt-2 text-sm text-green-700 dark:text-green-200/85">
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-7xl mx-auto px-6 lg:px-16 mb-6">
                <div class="rounded-md bg-red-50 p-4 dark:bg-red-500/10 dark:outline dark:outline-red-500/20">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 text-red-400">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Error</h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-200/85">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>
    
    <footer class="bg-white border-t border-slate-200">
        <div class="mx-auto max-w-7xl px-6 pt-20 pb-8 sm:pt-24 lg:px-8 lg:pt-32">
            <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                <div class="grid grid-cols-2 gap-8 xl:col-span-2">
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        <div>
                            <h3 class="text-sm/6 font-semibold text-[#0F172A]">Services</h3>
                            <ul role="list" class="mt-6 space-y-4">
                                <li>
                                    <a href="{{ route('doctors.index') }}" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">Find a Doctor</a>
                                </li>
                                <li>
                                    <a href="{{ route('home') }}" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">Specialties</a>
                                </li>
                                <li>
                                    <a href="#" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">Book Appointment</a>
                                </li>
                                <li>
                                    <a href="#" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">Telemedicine</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-10 md:mt-0">
                            <h3 class="text-sm/6 font-semibold text-[#0F172A]">Support</h3>
                            <ul role="list" class="mt-6 space-y-4">
                                <li>
                                    <a href="#" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">Help Center</a>
                                </li>
                                <li>
                                    <a href="#" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">Contact Us</a>
                                </li>
                                <li>
                                    <a href="#" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">FAQs</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        <div>
                            <h3 class="text-sm/6 font-semibold text-[#0F172A]">Company</h3>
                            <ul role="list" class="mt-6 space-y-4">
                                <li>
                                    <a href="#" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">About Us</a>
                                </li>
                                <li>
                                    <a href="#" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">Blog</a>
                                </li>
                                <li>
                                    <a href="#" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">Careers</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-10 md:mt-0">
                            <h3 class="text-sm/6 font-semibold text-[#0F172A]">Legal</h3>
                            <ul role="list" class="mt-6 space-y-4">
                                <li>
                                    <a href="#" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">Terms of Service</a>
                                </li>
                                <li>
                                    <a href="#" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">Privacy Policy</a>
                                </li>
                                <li>
                                    <a href="#" class="text-sm/6 text-[#64748B] hover:text-[#0F172A]">HIPAA Compliance</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mt-10 xl:mt-0">
                    <h3 class="text-sm/6 font-semibold text-[#0F172A]">Stay Updated</h3>
                    <p class="mt-2 text-sm/6 text-[#64748B]">Get health tips and appointment reminders delivered to your inbox.</p>
                    <form class="mt-6 sm:flex sm:max-w-md">
                        <label for="email-address" class="sr-only">Email address</label>
                        <input id="email-address" type="email" name="email-address" required placeholder="Enter your email" autocomplete="email" class="w-full min-w-0 rounded-md bg-white px-3 py-1.5 text-base text-[#0F172A] outline-1 -outline-offset-1 outline-slate-300 placeholder:text-[#64748B] focus:outline-2 focus:-outline-offset-2 focus:outline-sky-600 sm:w-64 sm:text-sm/6 xl:w-full" />
                        <div class="mt-4 sm:mt-0 sm:ml-4 sm:shrink-0">
                            <button type="submit" class="inline-flex items-center justify-center gap-x-1.5 w-full rounded-md bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-sky-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 dark:bg-sky-500 dark:shadow-none dark:hover:bg-sky-400 dark:focus-visible:outline-sky-500">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mt-16 border-t border-slate-200 pt-8 sm:mt-20 md:flex md:items-center md:justify-between lg:mt-24">
                <div class="flex gap-x-6 md:order-2">
                    <a href="#" class="text-[#64748B] hover:text-[#0F172A]">
                        <span class="sr-only">Facebook</span>
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="size-6">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" fill-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="text-[#64748B] hover:text-[#0F172A]">
                        <span class="sr-only">Instagram</span>
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="size-6">
                            <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" fill-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="text-[#64748B] hover:text-[#0F172A]">
                        <span class="sr-only">X</span>
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="size-6">
                            <path d="M13.6823 10.6218L20.2391 3H18.6854L12.9921 9.61788L8.44486 3H3.2002L10.0765 13.0074L3.2002 21H4.75404L10.7663 14.0113L15.5685 21H20.8131L13.6819 10.6218H13.6823ZM11.5541 13.0956L10.8574 12.0991L5.31391 4.16971H7.70053L12.1742 10.5689L12.8709 11.5655L18.6861 19.8835H16.2995L11.5541 13.096V13.0956Z" />
                        </svg>
                    </a>
                </div>
                <p class="mt-8 text-sm/6 text-[#64748B] md:order-1 md:mt-0">&copy; {{ date('Y') }} ZapCare. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
        
        // Re-initialize icons after Alpine.js updates
        document.addEventListener('alpine:init', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
