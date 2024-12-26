@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add Product</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.products') }}">
                        <div class="text-tiny">Products</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Add Product</div></li>
            </ul>
        </div>
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{ route('admin.product.store') }}">
            @csrf
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Product name <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product name" name="name" required>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" required>
                </fieldset>

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Category <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="category_id" required>
                                <option value="">Choose Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="brand">
                        <div class="body-title mb-10">Brand <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="brand_id" required>
                                <option value="">Choose Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description" required></textarea>
                </fieldset>

                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="description" required></textarea>
                </fieldset>
            </div>

            <div class="wg-box">
                <fieldset>
                    <div class="body-title">Upload Image <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="image">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">Drop your image here or select 
                                    <span class="tf-color">click to browse</span>
                                </span>
                                <input type="file" id="image" name="image" accept="image/*" required>
                            </label>
                        </div>
                        <div id="image-preview" class="preview mt-10"></div>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="body-title mb-10">Upload Gallery Images</div>
                    <div class="upload-image mb-16">
                        <!-- <div class="item">
        <img src="images/upload/upload-1.png" alt="">
    </div>                                                 -->
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="text-tiny">Drop your images here or select <span
                                        class="tf-color">click to browse</span></span>
                                <input type="file" id="gFile" name="images[]" accept="image/*"
                                    multiple="">
                            </label>
                        </div>
                    </div>
                </fieldset>


                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price" required>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price" required>
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" required>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity" required>
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock</div>
                        <div class="select mb-10">
                            <select name="stock_status">
                                <option value="instock">InStock</option>
                                <option value="outofstock">Out of Stock</option>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <div class="select mb-10">
                            <select name="featured">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Add product</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Single Image Preview
            $('#image').on('change', function() {
                const [file] = this.files;
                const previewContainer = $('#image-preview');
                previewContainer.empty(); // Clear previous preview
                if (file) {
                    const img = $('<img>')
                        .attr('src', URL.createObjectURL(file))
                        .css({
                            maxWidth: '200px',
                            maxHeight: '200px',
                            border: '1px solid #ddd',
                            padding: '5px',
                            borderRadius: '5px'
                        });
                    previewContainer.append(img);
                }
            });

            // $(document).ready(function() {
            //     // Function to handle multiple image previews and prepend them to the gallery
            //     function previewGalleryImages(files) {
            //         // Create and clear the gallery preview container
            //         let galleryPreviewContainer = $('#gallery-preview');
            //         if (galleryPreviewContainer.length === 0) {
            //             galleryPreviewContainer = $(
            //                 '<div id="gallery-preview" class="preview mt-10"></div>');
            //             $('body').append(
            //             galleryPreviewContainer); // Append the gallery container to the body or another parent
            //         }
            //         galleryPreviewContainer.empty(); // Clear any previous previews

            //         // Use map to process all selected files and generate previews
            //         Array.from(files).map((file, index) => {
            //             const fileURL = URL.createObjectURL(file); // Generate URL for the file
            //             const img = $('<img>') // Create an image element
            //                 .attr('src', fileURL)
            //                 .attr('alt', `Image ${index + 1}`)
            //                 .css({
            //                     maxWidth: '100px',
            //                     maxHeight: '100px',
            //                     margin: '5px',
            //                     border: '1px solid #ddd',
            //                     padding: '5px',
            //                     borderRadius: '5px'
            //                 });

            //             // Prepend each image preview to the container
            //             galleryPreviewContainer.prepend(img); // Prepend instead of append
            //         });
            //     }

            //     // Trigger the previewGalleryImages function when files are selected
            //     $('#gallery_images').on('change', function() {
            //         const files = this.files;
            //         if (files && files.length > 0) {
            //             previewGalleryImages(files); // Call the function with the selected files
            //         }
            //     });
            // });

            $("#gFile").on("change", function(e) {
                const photoInp = $("gFile");
                const gphotos = this.files;
                $.each(gphotos, function(key,val) {
                    $("#galUpload").prepend(`<div class="item gitems"><img src="${URL.createObjectURL(val)}" /> </div>`);
                })
            })


        });
    </script>
@endpush