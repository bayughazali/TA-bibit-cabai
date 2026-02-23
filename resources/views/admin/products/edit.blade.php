@extends('admin.layouts.app')

@section('title', 'Edit Produk')

@section('content')
<!-- Back Button -->
<a href="{{ route('admin.products.index') }}" class="back-button">
    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Produk
</a>
    <!-- Main Content -->
    <div class="main-content">
        <div class="admin-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.products.index') }}">Kelola Produk</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Produk</li>
                </ol>
            </nav>
            <h2>Edit Produk</h2>
            <p class="mb-0">Edit informasi produk {{ $product->name }}</p>
        </div>

        <div class="content-area fade-in">
            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Form Edit Produk
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Nama Produk -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Sayuran" {{ old('category', $product->category) === 'Sayuran' ? 'selected' : '' }}>Sayuran</option>
                                    <option value="Buah" {{ old('category', $product->category) === 'Buah' ? 'selected' : '' }}>Buah</option>
                                    <option value="Herbal" {{ old('category', $product->category) === 'Herbal' ? 'selected' : '' }}>Herbal</option>
                                    <option value="Hias" {{ old('category', $product->category) === 'Hias' ? 'selected' : '' }}>Hias</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Harga -->
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $product->price) }}" 
                                           min="0" step="0.01" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stok -->
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock', $product->stock) }}" 
                                       min="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="aktif" {{ old('status', $product->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $product->status) === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Label -->
                            <div class="col-md-6 mb-3">
                                <label for="label" class="form-label">Label</label>
                                <select class="form-select @error('label') is-invalid @enderror" id="label" name="label">
                                    <option value="">Tidak Ada Label</option>
                                    <option value="baru" {{ old('label', $product->label) === 'baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="populer" {{ old('label', $product->label) === 'populer' ? 'selected' : '' }}>Populer</option>
                                    <option value="diskon" {{ old('label', $product->label) === 'diskon' ? 'selected' : '' }}>Diskon</option>
                                    <option value="terbatas" {{ old('label', $product->label) === 'terbatas' ? 'selected' : '' }}>Terbatas</option>
                                </select>
                                @error('label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gambar Saat Ini -->
                            @if($product->image_url)
                            <div class="col-12 mb-3">
                                <label class="form-label">Gambar Saat Ini</label>
                                <div class="mb-3">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                         class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                            @endif

                            <!-- Upload Gambar Baru -->
                            <div class="col-12 mb-3">
                                <label for="image" class="form-label">Gambar Produk (Opsional)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                <div class="form-text">Format: JPG, PNG, JPEG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-warning me-2">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Update Produk
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


<style>
.back-button {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1100;
    background: linear-gradient(45deg, #ffc107, #e0a800);
    color: #212529;
    border: none;
    padding: 12px 20px;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
}

.back-button:hover {
    background: linear-gradient(45deg, #e0a800, #c69500);
    transform: translateY(-2px);
    color: #212529;
    text-decoration: none;
}

.card {
    border: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-radius: 12px;
}

.card-header {
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid #dee2e6;
    border-radius: 12px 12px 0 0 !important;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.text-danger {
    color: #dc3545 !important;
}

.fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection