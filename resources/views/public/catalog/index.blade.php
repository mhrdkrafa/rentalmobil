@extends('layouts.public')

@section('title', 'Katalog Armada | AutoRent')

@section('content')
<div class="pt-24 pb-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row gap-8 mt-8">
            <!-- Sidebar Filters -->
            <div class="w-full md:w-1/4">
                <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100 sticky top-28">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Filter Pencarian</h2>
                    
                    <form action="{{ route('public.catalog.index') }}" method="GET">
                        <!-- Category -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select name="category" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 transition">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Transmission -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Transmisi</label>
                            <select name="transmission" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 transition">
                                <option value="">Semua Transmisi</option>
                                <option value="automatic" {{ request('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                                <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga / Hari (Maksimal)</label>
                            <input type="range" name="max_price" min="200000" max="3000000" step="100000" 
                                   value="{{ request('max_price', 3000000) }}" 
                                   class="w-full accent-primary-600"
                                   oninput="document.getElementById('price_display').innerText = 'Rp ' + parseInt(this.value).toLocaleString('id-ID')">
                            <div class="text-right text-sm text-primary-600 font-bold mt-2" id="price_display">
                                Rp {{ number_format(request('max_price', 3000000), 0, ',', '.') }}
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-md transition-colors">
                            Terapkan Filter
                        </button>
                        
                        @if(request()->anyFilled(['category', 'transmission', 'max_price']))
                            <a href="{{ route('public.catalog.index') }}" class="block text-center w-full py-3 mt-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-xl shadow-sm transition-colors">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Vehicle Grid -->
            <div class="w-full md:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Armada Tersedia</h1>
                    <span class="text-gray-500">{{ $vehicles->total() }} kendaraan ditemukan</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($vehicles as $vehicle)
                        <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col border border-gray-100">
                            <div class="relative h-48 overflow-hidden">
                                @if($vehicle->images->count() > 0)
                                    <img src="{{ asset('storage/' . $vehicle->images->first()->image_path) }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-gray-900 shadow-sm">
                                    {{ $vehicle->category->name }}
                                </div>
                            </div>
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 truncate" title="{{ $vehicle->name }}">{{ $vehicle->name }}</h3>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ ucfirst($vehicle->transmission->value) }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $vehicle->capacity }} Kursi
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-2 pt-4 border-t border-gray-100">
                                    <div>
                                        <div class="text-lg font-bold text-primary-600">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}<span class="text-xs text-gray-400 font-normal">/hari</span></div>
                                    </div>
                                    <a href="{{ route('public.catalog.show', $vehicle->id) }}" class="bg-gray-900 hover:bg-primary-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors shadow-sm">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-2 xl:col-span-3 text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada mobil ditemukan</h3>
                            <p class="mt-1 text-sm text-gray-500">Coba ubah kriteria filter Anda.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $vehicles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
