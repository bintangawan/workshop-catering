<form class="grid gap-4" method="POST" action="{{ $editingMenu ? route('admin.menus.update', $editingMenu) : route('admin.menus.store') }}">
    @csrf
    @if ($editingMenu) @method('PATCH') @endif
    <label class="field"><span>Nama menu</span><input name="name" maxlength="150" value="{{ old('name', $editingMenu?->name) }}" required></label>
    @error('name') <span class="text-sm text-red-700" role="alert">{{ $message }}</span> @enderror
    <label class="field"><span>Harga per porsi (Rp)</span><input name="price" type="number" min="1" max="4294967295" step="1" value="{{ old('price', $editingMenu?->price) }}" required></label>
    @error('price') <span class="text-sm text-red-700" role="alert">{{ $message }}</span> @enderror
    <label class="field"><span>Deskripsi</span><textarea name="description" maxlength="1000" rows="3">{{ old('description', $editingMenu?->description) }}</textarea></label>
    @error('description') <span class="text-sm text-red-700" role="alert">{{ $message }}</span> @enderror
    <input type="hidden" name="is_active" value="0">
    <label class="check-field"><input name="is_active" type="checkbox" value="1" @checked(old('is_active', $editingMenu?->is_active ?? true))> Aktif di katalog</label>
    @error('is_active') <span class="text-sm text-red-700" role="alert">{{ $message }}</span> @enderror
    <button class="button w-fit" type="submit">Simpan menu</button>
</form>
