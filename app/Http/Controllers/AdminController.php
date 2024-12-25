<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Dashboard view
    public function index()
    {
        return view('admin.index');
    }

    // List all brands
    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    // Add new brand view
    public function add_brand()
    {
        return view('admin.brand-add');
    }

    // Store a new brand
    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->getClientOriginalExtension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;

            // Generate the thumbnail using PHP's GD functions
            $this->generateThumbnail($image->path(), $file_name);

            $brand->image = $file_name;
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand has been added successfully!');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]); 

        $brand = Brand::findOrFail($id);

        $brand->name = $request->name;
        $brand->slug = $request->slug;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/brands'), $filename);

            // Remove old image
            if ($brand->image && file_exists(public_path('uploads/brands/' . $brand->image))) {
                unlink(public_path('uploads/brands/' . $brand->image));
            }

            $brand->image = $filename;
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand updated successfully!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        // Delete the image if it exists
        if ($brand->image && file_exists(public_path('uploads/brands/' . $brand->image))) {
            unlink(public_path('uploads/brands/' . $brand->image));
        }

        $brand->delete();

        return redirect()->route('admin.brands')->with('status', 'Brand deleted successfully!');
    }

    // Generate thumbnails using PHP's GD functions
    private function generateThumbnail($imagePath, $imageName)
    {
        $destinationPath = public_path('uploads/brands');

        // Ensure the directory exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Load the image based on its type
        [$width, $height, $imageType] = getimagesize($imagePath);
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($imagePath);
                break;
            default:
                throw new \Exception('Unsupported image type');
        }

        // Create a blank canvas for the thumbnail
        $thumbnailWidth = 124;
        $thumbnailHeight = 124;
        $thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

        // Maintain transparency for PNG images
        if ($imageType === IMAGETYPE_PNG) {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
        }

        // Resize and crop the image
        imagecopyresampled(
            $thumbnail,
            $sourceImage,
            0, 0, 0, 0,
            $thumbnailWidth, $thumbnailHeight,
            $width, $height
        );

        // Save the thumbnail to the destination path
        $outputPath = $destinationPath . '/' . $imageName;
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($thumbnail, $outputPath);
                break;
            case IMAGETYPE_PNG:
                imagepng($thumbnail, $outputPath);
                break;
        }

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($thumbnail);
    }

    // Categories CRUD

    public function categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function add_category()
    {
        return view('admin.category.add');
    }

    public function store_category(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);
    
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug);
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/categories'), $fileName);
            $category->image = $fileName;
        }
    
        $category->save();
    
        return redirect()->route('admin.categories')->with('status', 'Category has been added successfully!');
    }
    

    public function edit_category($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update_category(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);
    
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->slug = $request->slug;
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/categories'), $fileName);
    
            // Remove old image
            if ($category->image && file_exists(public_path('uploads/categories/' . $category->image))) {
                unlink(public_path('uploads/categories/' . $category->image));
            }
    
            $category->image = $fileName;
        }
    
        $category->save();
    
        return redirect()->route('admin.categories')->with('status', 'Category updated successfully!');
    }
    

    public function destroy_category($id)
    {
        $category = Category::findOrFail($id);
        // Delete the image if it exists
        if ($category->image && file_exists(public_path('uploads/categories/' . $category->image))) {
            unlink(public_path('uploads/categories/' . $category->image));
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('status', 'Category deleted successfully!');
    }
}
