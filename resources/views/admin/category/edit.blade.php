@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Category Information</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories') }}">
                            <div class="text-tiny">Categories</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit Category</div>
                    </li>
                </ul>
            </div>

            <!-- Edit Category Form -->
            <div class="wg-box">
                <form class="form-new-product form-style-1" action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Category Name Field -->
                    <fieldset class="name">
                        <div class="body-title">Category Name <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Category name" name="name" value="{{ old('name', $category->name) }}" required>
                    </fieldset>
                    @error('name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <!-- Category Slug Field -->
                    <fieldset class="name">
                        <div class="body-title">Category Slug <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Category Slug" name="slug" value="{{ old('slug', $category->slug) }}" required>
                    </fieldset>
                    @error('slug')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <!-- Image Upload Field -->
                    <fieldset>
                        <div class="body-title">Upload Image</div>
                        <div class="upload-image flex-grow">
                            <!-- Image Preview -->
                            <div class="item" id="imgpreview" style="{{ $category->image ? '' : 'display:none' }}">
                                <img src="{{ $category->image ? asset('uploads/categories/' . $category->image) : '#' }}" class="effect8" alt="Preview">
                            </div>
                            <!-- Upload Button -->
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="categoryImage">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                    <input type="file" id="categoryImage" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('image')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <!-- Submit Button -->
                    <div class="bot">
                        <button class="tf-button w208" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Image Preview on File Select
            $("#categoryImage").on("change", function(e) {
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            // Auto-generate Slug from Name
            $("input[name='name']").on("input", function() {
                const slug = StringToSlug($(this).val());
                $("input[name='slug']").val(slug);
            });
        });

        // Function to Convert Text to Slug
        function StringToSlug(text) {
            return text
                .toLowerCase()
                .replace(/[^\w ]+/g, "")
                .replace(/ +/g, "-");
        }
    </script>
@endpush
