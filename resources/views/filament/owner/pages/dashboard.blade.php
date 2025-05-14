<x-filament::page>
    <div class="space-y-8">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-black">
                        Halo, {{ auth()->user()->nama_pegawai }}!
                    </h1>
                    <p class="text-emerald-100 mt-1">Selamat datang di ReuseMart. Yuk, kelola data dan pantau aktivitas
                        organisasi kamu di sini.</p>
                </div>
                <div class="hidden md:block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-emerald-200 opacity-50" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
            </div>
        </div>
</x-filament::page>
