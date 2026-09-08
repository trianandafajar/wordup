@props([
    'active' => 'learn',
])

<nav class="hidden lg:flex fixed left-0 top-0 bottom-0 w-64 border-r border-gray-200 bg-white flex-col p-6 z-40">
    <div class="mb-10 text-2xl font-extrabold text-brand-500 tracking-wider">WordUp</div>
    
    <div class="flex flex-col gap-2">
        <a href="/" class="flex items-center gap-4 p-3 rounded-xl font-semibold transition-colors {{ $active === 'home' ? 'bg-brand-50 text-brand-600' : 'text-gray-600 hover:bg-gray-100' }}">
            <x-heroicon-o-home class="w-6 h-6" /> Home
        </a>
        <a href="{{ route('user.learn') }}" class="flex items-center gap-4 p-3 rounded-xl font-semibold transition-colors {{ $active === 'learn' ? 'bg-brand-50 text-brand-600' : 'text-gray-600 hover:bg-gray-100' }}">
            <x-heroicon-o-book-open class="w-6 h-6" /> Learn
        </a>
        <a href="{{ route('user.leaderboard') }}" class="flex items-center gap-4 p-3 rounded-xl font-semibold transition-colors {{ $active === 'rank' ? 'bg-brand-50 text-brand-600' : 'text-gray-600 hover:bg-gray-100' }}">
            <x-heroicon-o-trophy class="w-6 h-6" /> Leaderboard
        </a>
        <a href="{{ route('user.profile') }}" class="flex items-center gap-4 p-3 rounded-xl font-semibold transition-colors {{ $active === 'profile' ? 'bg-brand-50 text-brand-600' : 'text-gray-600 hover:bg-gray-100' }}">
            <x-heroicon-o-user class="w-6 h-6" /> Profile
        </a>
    </div>
</nav>
