<div x-show="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black opacity-50 z-20 no-print" x-cloak></div>

<div class="w-64 bg-gray-800 text-white h-screen shadow-lg fixed top-0 left-0 flex flex-col z-30 transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 no-print"
     :class="{'translate-x-0': sidebarOpen}">
    
    <div class="p-6 flex justify-between items-center">
        <a href="{{ route('dashboard') }}">
            <h1 class="text-2xl font-bold text-white">Aplikasi Pesantren</h1>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white focus:outline-none">
            <i class="fas fa-times fa-lg"></i>
        </button>
    </div>

    <nav class="mt-4 flex-grow">
        <span class="px-6 text-gray-400 text-xs uppercase">Menu</span>
        <ul class="mt-2">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }} hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-tachometer-alt w-6 text-center"></i>
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('santri.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('santri.*') ? 'bg-gray-700' : '' }} hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-users w-6 text-center"></i>
                    <span class="ml-4">Data Santri</span>
                </a>
            </li>
            <li>
                <a href="{{ route('perizinan.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('perizinan.*') ? 'bg-gray-700' : '' }} hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-file-alt w-6 text-center"></i>
                    <span class="ml-4">Perizinan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('tagihan.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('tagihan.*') ? 'bg-gray-700' : '' }} hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-dollar-sign w-6 text-center"></i>
                    <span class="ml-4">Tagihan</span>
                </a>
            </li>

            @if(auth()->user()->role == 'admin')
            <span class="px-6 text-gray-400 text-xs uppercase mt-4 block">Admin</span>
            <li class="mt-2">
                <a href="{{ route('users.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('users.*') ? 'bg-gray-700' : '' }} hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-user-cog w-6 text-center"></i>
                    <span class="ml-4">Manajemen User</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>

    <div class="w-full">
         <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();"
               class="flex items-center px-6 py-3 text-red-400 hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-sign-out-alt w-6 text-center"></i>
                <span class="ml-4">Logout</span>
            </a>
        </form>
    </div>
</div>