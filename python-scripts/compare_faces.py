import requests

def compare_faces(ktp_image_path, selfie_image_path):
    url = 'https://api-us.faceplusplus.com/facepp/v3/compare'
    api_key = 'wAmOrD6C3BwvEuQPFHFsSL3xj7h8JzvG'
    api_secret = 'ihok6x2aOXneQDcEnJ6av-KAcNnbUwn_'

    files = {
        'image_file1': open(ktp_image_path, 'rb'),
        'image_file2': open(selfie_image_path, 'rb')
    }

    data = {
        'api_key': api_key,
        'api_secret': api_secret
    }

    response = requests.post(url, data=data, files=files)

    if response.status_code != 200:
        print("HTTP Error:", response.status_code, response.text)
        return False

    result = response.json()

    if 'error_message' in result:
        print('Face++ Error:', result['error_message'])
        return False

    confidence = result.get('confidence', 0)
    print('Confidence:', confidence)

    if confidence > 60:
        print("✅ Wajah cocok!")
        return True
    else:
        print("❌ Wajah tidak cocok.")
        return False

# Contoh penggunaan
compare_faces('public/uploads/identitas/ktp_user123.jpg', 'public/uploads/live/selfie_user123.jpg')
