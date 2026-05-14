<x-layouts.app title="Edit Poli">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('polis.index') }}" class="inline-flex items-center justify-center w-9 h-9 
                  rounded-lg bg-slate-100 text-slate-500 
                  hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        <h2 class="text-2xl font-bold text-slate-800">
            Edit Poli
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">

        <div class="card-body p-8">

            <form action="{{ route('polis.update', $poli->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama Poli --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Nama Poli <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="nama_poli" value="{{ old('nama_poli', $poli->nama_poli) }}"
                        placeholder="Masukkan nama poli..."
                        class="w-full px-4 py-2 rounded-lg border-2 
                               @error('nama_poli') border-red-500 @else border-slate-300 @enderror
                               focus:border-primary focus:outline-none"
                        required>

                    @error('nama_poli')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Keterangan <span class="text-red-500">*</span>
                    </label>

                    <textarea name="keterangan" rows="4" placeholder="Masukkan keterangan poli..."
                        class="w-full px-4 py-2 rounded-lg border-2 
                               @error('keterangan') border-red-500 @else border-slate-300 @enderror
                               focus:border-primary focus:outline-none"
                        required>{{ old('keterangan', $poli->keterangan) }}</textarea>

                    @error('keterangan')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Button --}}
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-2.5 bg-[#2d4499] hover:bg-[#1e2d6b] text-white rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>

                    <a href="{{ route('polis.index') }}"
                        class="flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-sm font-semibold transition">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</x-layouts.app>
