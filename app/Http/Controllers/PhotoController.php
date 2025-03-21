<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
     * Fotoğraf ve videoları yükler ve veritabanına kaydeder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadPhotos(Request $request)
    {
        // Kullanıcının IP adresini al
        $ip = str_replace('.', '_', $request->ip()); // Dosya yolu için uygun hale getir
        $folderPath = 'uploads/' . $ip;

        // Eğer belirtilen klasör yoksa oluştur
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        // Fotoğrafları ve videoları işle
        if ($request->hasFile('photos') && is_array($request->file('photos'))) {
            foreach ($request->file('photos') as $file) {
                if ($file->isValid()) {
                    // Dosya türünü kontrol et (fotoğraf veya video)
                    $mimeType = $file->getMimeType();
                    $fileType = str_starts_with($mimeType, 'image') ? 'image' : 'video';

                    // Benzersiz bir dosya adı oluştur
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Dosya türüne göre işlem yap
                    if ($fileType === 'image') {
                        // Fotoğrafı kalite kaybı olmadan kaydet
                        $image = Image::make($file);
                        $image->save(storage_path('app/public/' . $folderPath . '/' . $fileName), 100); // %100 kalite
                    } else {
                        // Videoyu doğrudan kaydet
                        $file->storeAs('public/' . $folderPath, $fileName);
                    }

                    // Veritabanına kaydet
                    Photo::create([
                        'file_path' => $folderPath . '/' . $fileName,
                        'file_type' => $fileType, // Dosya türünü kaydet
                    ]);
                }
            }
        }

        // Başarı mesajı ile geri dön
        return redirect()->back()->with('success', 'Fotoğraf ve videolar başarıyla yüklendi!');
    }}
