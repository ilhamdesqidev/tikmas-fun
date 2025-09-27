<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilities = Facility::latest()->paginate(10);
        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'duration' => 'required|string|max:100',
        'age_range' => 'required|string|max:100',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
    ]);

    // Upload gambar utama
    $imagePath = $request->file('image')->store('facilities', 'public');

    // Upload gallery images
    $galleryPaths = [];
    if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $galleryImage) {
            $galleryPaths[] = $galleryImage->store('facilities/gallery', 'public');
        }
    }

    Facility::create([
        'name' => $request->name,
        'description' => $request->description,
        'duration' => $request->duration,
        'age_range' => $request->age_range,
        'category' => 'wisata', // Default value
        'image' => $imagePath,
        'gallery_images' => !empty($galleryPaths) ? $galleryPaths : null
    ]);

    return redirect()->route('admin.facilities.index')
        ->with('success', 'Fasilitas berhasil ditambahkan.');
}

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        return view('admin.facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string|max:100',
            'age_range' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'duration' => $request->duration,
            'age_range' => $request->age_range,
            'category' => $request->category
        ];

        // Jika ada gambar utama baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($facility->image) {
                Storage::disk('public')->delete($facility->image);
            }
            // Upload gambar baru
            $data['image'] = $request->file('image')->store('facilities', 'public');
        }

        // Jika ada gallery images baru
        if ($request->hasFile('gallery_images')) {
            // Hapus gallery images lama
            if ($facility->gallery_images) {
                foreach ($facility->gallery_images as $oldGalleryImage) {
                    Storage::disk('public')->delete($oldGalleryImage);
                }
            }
            
            // Upload gallery images baru
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $galleryImage) {
                $galleryPaths[] = $galleryImage->store('facilities/gallery', 'public');
            }
            $data['gallery_images'] = $galleryPaths;
        }

        $facility->update($data);

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        // Hapus gambar utama
        if ($facility->image) {
            Storage::disk('public')->delete($facility->image);
        }

        // Hapus gallery images
        if ($facility->gallery_images) {
            foreach ($facility->gallery_images as $galleryImage) {
                Storage::disk('public')->delete($galleryImage);
            }
        }

        $facility->delete();

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}