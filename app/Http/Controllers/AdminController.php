<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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
}
