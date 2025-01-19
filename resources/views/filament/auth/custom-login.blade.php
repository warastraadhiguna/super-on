<x-filament-panels::page>
    <div class="flex min-h-screen items-center justify-center bg-gray-100 dark:bg-gray-900 p-6">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
            <!-- 🔥 Logo -->
            <div class="flex justify-center">
                <img src="{{ asset('storage/uploads/logo.png') }}" 
                     alt="Logo" 
                     class="w-24 h-auto mb-4">
            </div>

            <h2 class="text-center text-xl font-bold text-gray-900 dark:text-white">
                Selamat Datang di Sistem
            </h2>

            <p class="text-center text-gray-600 dark:text-gray-300 mb-6">
                Silakan masuk untuk melanjutkan
            </p>

            <!-- 🔥 Form Login -->
            <div class="space-y-4">
                @livewire(\Filament\Http\Livewire\Auth\Login::class)
            </div>

            <!-- 🔥 Footer -->
            <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                Hak Cipta © {{ date('Y') }} - Sistem Informasi Sekolah
            </div>
        </div>
    </div>
</x-filament-panels::page>
