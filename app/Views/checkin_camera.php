<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Wajah - Check-In</title>
</head>
<body>
    <h2>Scan Wajah untuk Verifikasi</h2>
    <video id="video" width="400" height="300" autoplay></video>
    <br>
    <button onclick="takeSnapshot()">Ambil Foto & Kirim</button>
    <canvas id="canvas" width="400" height="300" style="display:none;"></canvas>

    <form id="uploadForm" method="post" action="<?= base_url('checkin/verifikasi-wajah') ?>">
        <input type="hidden" name="user_id" value="<?= $user_id ?>">
        <input type="hidden" name="image_data" id="image_data">
    </form>

    <script>
        const video = document.getElementById('video');
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => video.srcObject = stream)
            .catch(err => console.error("Camera error:", err));

        function takeSnapshot() {
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, 400, 300);

            const dataUrl = canvas.toDataURL('image/jpeg');
            document.getElementById('image_data').value = dataUrl;

            document.getElementById('uploadForm').submit();
        }
    </script>
</body>
</html>
