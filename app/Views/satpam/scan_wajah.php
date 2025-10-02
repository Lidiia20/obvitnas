<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Wajah - GITET New Ujung Berung</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        #video, #canvas {
            width: 100%;
            max-width: 480px;
            border-radius: 8px;
            border: 2px solid #007bff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        button {
            margin: 5px;
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #capture-btn {
            background-color: #007bff;
            color: white;
        }
        #capture-btn:hover {
            background-color: #0056b3;
        }
        #retake-btn {
            background-color: #6c757d;
            color: white;
        }
        #retake-btn:hover {
            background-color: #545b62;
        }
        #submit-btn {
            background-color: #28a745;
            color: white;
        }
        #submit-btn:hover {
            background-color: #1e7e34;
        }
        img {
            border-radius: 8px;
            border: 2px solid #007bff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .instructions {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .tips {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .tips ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .tips li {
            margin: 5px 0;
        }
        .photo-comparison {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .photo-section {
            flex: 1;
            min-width: 250px;
        }
        .photo-section h4 {
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔍 Verifikasi Wajah untuk: <?= esc($kunjungan['nama']) ?></h2>
        
        <div class="instructions">
            <h4>📋 Instruksi Verifikasi:</h4>
            <p>Silakan ambil foto wajah Anda yang jelas dan terang untuk dibandingkan dengan foto identitas yang sudah terdaftar.</p>
        </div>

        <div class="tips">
            <h4>💡 Tips untuk Foto yang Baik:</h4>
            <ul>
                <li><strong>Pencahayaan:</strong> Pastikan wajah terang dan tidak ada bayangan gelap</li>
                <li><strong>Jarak:</strong> Dekatkan wajah ke kamera (sekitar 30-50 cm)</li>
                <li><strong>Posisi:</strong> Hadap kamera dengan wajah lurus, tidak miring</li>
                <li><strong>Ekspresi:</strong> Tampilkan wajah netral seperti di foto KTP</li>
                <li><strong>Fokus:</strong> Pastikan kamera tidak blur dan wajah terlihat jelas</li>
                <li><strong>Latar:</strong> Hindari latar belakang yang terlalu ramai</li>
            </ul>
        </div>

        <div class="photo-comparison">
            <div class="photo-section">
            <h4>📷 Foto Selfie</h4>
            <img src="<?= base_url('public/uploads/selfie/' . $kunjungan['foto_selfie']) ?>" 
                alt="Foto Selfie" width="250">
        </div>

            
            <div class="photo-section">
                <h4>📹 Foto Live (Webcam)</h4>
                <video id="video" autoplay playsinline></video>
                <canvas id="canvas" style="display: none;"></canvas>
                <br><br>
                
                <!-- Tombol Kontrol -->
                <button id="capture-btn">📸 Ambil Foto</button>
                <button id="retake-btn" style="display:none;">🔄 Ulangi Foto</button>
                <button id="submit-btn" style="display:none;">✅ Kirim untuk Verifikasi</button>
            </div>
        </div>

        <!-- Form untuk Submit -->
        <form id="face-form" action="<?= base_url('satpam/verifikasiWajah') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="kunjungan_id" value="<?= $kunjungan['id'] ?>">
            <input type="hidden" name="foto_terdaftar" value="<?= base_url('public\public\uploads/selfie/' . $kunjungan['foto_selfie']) ?>">
            <input type="hidden" name="image_webcam" id="image_webcam">
        </form>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('capture-btn');
        const retakeBtn = document.getElementById('retake-btn');
        const submitBtn = document.getElementById('submit-btn');
        const imageInput = document.getElementById('image_webcam');
        const form = document.getElementById('face-form');

        // Aktifkan webcam dengan resolusi yang lebih tinggi
        navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: 'user'
            } 
        })
        .then(stream => {
            video.srcObject = stream;
        }).catch(err => {
            alert("❌ Tidak dapat mengakses webcam: " + err.message);
        });

        // Ambil Foto
        captureBtn.addEventListener('click', function () {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/jpeg', 0.9); // Kualitas 90%
            imageInput.value = dataUrl;

            canvas.style.display = 'block';
            video.style.display = 'none';
            captureBtn.style.display = 'none';
            retakeBtn.style.display = 'inline-block';
            submitBtn.style.display = 'inline-block';
        });

        // Ulangi Foto
        retakeBtn.addEventListener('click', function () {
            canvas.style.display = 'none';
            video.style.display = 'block';
            captureBtn.style.display = 'inline-block';
            retakeBtn.style.display = 'none';
            submitBtn.style.display = 'none';
        });

        // Submit ke Backend
        submitBtn.addEventListener('click', function () {
            if (imageInput.value) {
                submitBtn.textContent = '⏳ Memproses...';
                submitBtn.disabled = true;
                form.submit();
            } else {
                alert("❌ Silakan ambil foto terlebih dahulu.");
            }
        });
    </script>
</body>
</html>
