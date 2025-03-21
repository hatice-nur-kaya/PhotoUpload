<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hatice Nur & Batuhan - Nişanımıza Hoş Geldiniz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ff9a9e, #fad0c4);
            text-align: center;
            font-family: 'Georgia', serif;
            color: #4a4a4a;
        }
        .container {
            margin-top: 50px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            color: #d63384;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 22px;
            color: #6c757d;
            margin-bottom: 20px;
        }
        .btn-upload {
            background-color: #d63384;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 18px;
            transition: background 0.3s ease;
        }
        .btn-upload:hover {
            background-color: #b82b6c;
        }
        .privacy-notice {
            font-size: 14px;
            color: #6c757d;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="title">Hatice Nur & Batuhan</h1>
    <h2 class="subtitle">Nişanımıza Hoş Geldiniz!</h2>
    <p>Bu özel günü ölümsüzleştirmek için en güzel anılarınızı bizimle paylaşabilirsiniz. 💕</p>
    <form action="{{ route('upload.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="photos" class="form-label">Fotoğraf ve videolarınızı seçin (Çoklu seçim yapabilirsiniz):</label>
            <input type="file" name="photos[]" multiple class="form-control" accept="image/*, video/*">        </div>
        <button type="submit" class="btn btn-upload">📸 Fotoğraf ve Videoları Yükle</button>
    </form>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            🎉 {{ session('success') }}
        </div>
    @endif

    <div class="privacy-notice">
        <p>📌 <strong>Gizlilik Esastır:</strong> Yüklediğiniz fotoğraf ve videolar sadece Hatice Nur ve Batuhan tarafından görülebilir. Başka hiçbir misafir yüklenen dosyaları göremez. Dosyalarınız güvende!</p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
