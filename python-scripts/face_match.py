import face_recognition
import sys
import os

# Ambil argumen dari terminal
# Contoh pemanggilan: python3 face_match.py 1753364518_abc123.jpg live.jpg
if len(sys.argv) != 3:
    print("args_error")
    sys.exit()

foto_database = sys.argv[1]  # ex: uploads/selfie/1753364518_abc123.jpg
foto_live = sys.argv[2]      # ex: uploads/live/capture.jpg

if not os.path.exists(foto_database) or not os.path.exists(foto_live):
    print("file_error")
    sys.exit()

try:
    img_db = face_recognition.load_image_file(foto_database)
    img_live = face_recognition.load_image_file(foto_live)

    db_encoding = face_recognition.face_encodings(img_db)[0]
    live_encoding = face_recognition.face_encodings(img_live)[0]

    result = face_recognition.compare_faces([db_encoding], live_encoding)

    if result[0]:
        print("match")
    else:
        print("no_match")

except Exception as e:
    print("error:", str(e))
