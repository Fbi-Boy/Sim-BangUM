@props(['name', 'role', 'subtitle' => null])
<div class="rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 p-6 text-white shadow-lg">
    <p class="text-sm opacity-80">Selamat datang kembali,</p>
    <h1 class="mt-1 text-2xl font-bold">{{ $name }}</h1>
    <p class="mt-1 text-sm opacity-90">{{ $subtitle ?? ucwords(str_replace('_', ' ', $role)) }}</p>
</div>
