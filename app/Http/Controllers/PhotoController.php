<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image; // Intervention Image kütüphanesi

class PhotoController extends Controller
{
    /**
     * Yükleme formunu gösterir.
     *
     * @return \Illuminate\View\View
     */
    public function showUploadForm()
    {
        return view('upload'); // upload.blade.php dosyasını döndürüyor
    }

    /**
     * Fotoğrafları yükler ve veritabanına kaydeder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadPhotos(Request $request)
    {
        // Fotoğraflar için doğrulama kuralları
        $request->validate([
            'photos' => 'required|array', // Dosya alanının bir dizi olduğunu doğrula
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:100000', // Max boyut 100MB
        ]);

        // Kullanıcının IP adresini al
        $ip = str_replace('.', '_', $request->ip()); // Dosya yolu için uygun hale getir
        $folderPath = 'uploads/' . $ip;
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        // Fotoğrafları işle
        if ($request->hasFile('photos') && is_array($request->file('photos'))) {
            foreach ($request->file('photos') as $photo) {
                if ($photo->isValid()) {
                    // Benzersiz bir dosya adı oluştur
                    $fileName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();

                    // Fotoğrafı kalite kaybı olmadan kaydet
                    $image = Image::make($photo);
                    $image->save(storage_path('app/public/' . $folderPath . '/' . $fileName), 100); // %100 kalite

                    // Veritabanına kaydet
                    Photo::create([
                        'file_path' => $folderPath . '/' . $fileName,
                    ]);
                }
            }
        }

        // Başarı mesajı ile geri dön
        return redirect()->back()->with('success', 'Fotoğraflar başarıyla yüklendi!');
    }
}
