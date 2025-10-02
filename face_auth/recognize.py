#!/usr/bin/env python3
"""
Script untuk melakukan face recognition/verifikasi wajah
Membandingkan foto live dengan encoding yang sudah tersimpan
"""

import face_recognition
import pickle
import cv2
import numpy as np
import base64
import json
import sys
import argparse
import logging
from pathlib import Path
import tempfile
import os

# Setup logging
logging.basicConfig(level=logging.ERROR, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

class FaceRecognizer:
    def __init__(self, encodings_file="encodings.pkl", tolerance=0.5):
        self.encodings_file = encodings_file
        self.tolerance = tolerance  # Threshold untuk matching (semakin kecil semakin strict)
        self.known_encodings = []
        self.known_names = []
        self.known_metadata = []
        
        # Load encodings
        self.load_encodings()
    
    def load_encodings(self):
        """
        Load encodings dari file pickle
        """
        try:
            if not os.path.exists(self.encodings_file):
                logger.error(f"File encodings tidak ditemukan: {self.encodings_file}")
                return False
            
            with open(self.encodings_file, 'rb') as f:
                data = pickle.load(f)
            
            self.known_encodings = data.get('encodings', [])
            self.known_names = data.get('names', [])
            self.known_metadata = data.get('metadata', [])
            
            logger.info(f"✅ Loaded {len(self.known_encodings)} encodings dari {self.encodings_file}")
            return True
            
        except Exception as e:
            logger.error(f"Error loading encodings: {str(e)}")
            return False
    
    def preprocess_image(self, image):
        """
        Preprocessing gambar untuk meningkatkan akurasi deteksi
        """
        try:
            # Jika input berupa path file
            if isinstance(image, (str, Path)):
                image = cv2.imread(str(image))
            
            if image is None:
                logger.error("Gambar tidak bisa dibaca")
                return None
            
            # Konversi ke RGB
            rgb_image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
            
            # Resize jika terlalu besar
            height, width = rgb_image.shape[:2]
            if width > 1024:
                scale = 1024 / width
                new_width = int(width * scale)
                new_height = int(height * scale)
                rgb_image = cv2.resize(rgb_image, (new_width, new_height), interpolation=cv2.INTER_AREA)
            
            # Histogram equalization untuk perbaikan kontras
            lab = cv2.cvtColor(rgb_image, cv2.COLOR_RGB2LAB)
            lab[:,:,0] = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8,8)).apply(lab[:,:,0])
            rgb_image = cv2.cvtColor(lab, cv2.COLOR_LAB2RGB)
            
            return rgb_image
            
        except Exception as e:
            logger.error(f"Error preprocessing image: {str(e)}")
            return None
    
    def verify_face_from_base64(self, base64_image, user_id, return_details=False):
        """
        Verifikasi wajah dari base64 string terhadap user tertentu
        
        Args:
            base64_image (str): Base64 encoded image
            user_id (str): ID user yang akan diverifikasi
            return_details (bool): Return detail confidence score
        
        Returns:
            dict: Hasil verifikasi dengan confidence score dan status
        """
        try:
            # Decode base64 image
            image_data = base64.b64decode(base64_image)
            
            # Simpan ke temporary file
            with tempfile.NamedTemporaryFile(delete=False, suffix='.jpg') as temp_file:
                temp_file.write(image_data)
                temp_path = temp_file.name
            
            try:
                result = self.verify_face_from_file(temp_path, user_id, return_details)
            finally:
                # Hapus temporary file
                if os.path.exists(temp_path):
                    os.unlink(temp_path)
            
            return result
            
        except Exception as e:
            logger.error(f"Error verifying face from base64: {str(e)}")
            return {
                'success': False,
                'verified': False,
                'error': str(e),
                'confidence': 0.0
            }
    
    def verify_face_from_file(self, image_path, user_id, return_details=False):
        """
        Verifikasi wajah dari file gambar terhadap user tertentu
        """
        try:
            if len(self.known_encodings) == 0:
                return {
                    'success': False,
                    'verified': False,
                    'error': 'Tidak ada encodings yang tersimpan',
                    'confidence': 0.0
                }
            
            # Cari encoding untuk user yang diminta
            if user_id not in self.known_names:
                return {
                    'success': False,
                    'verified': False,
                    'error': f'User {user_id} tidak ditemukan dalam database',
                    'confidence': 0.0
                }
            
            user_index = self.known_names.index(user_id)
            target_encoding = self.known_encodings[user_index]
            
            # Preprocess gambar live
            rgb_image = self.preprocess_image(image_path)
            if rgb_image is None:
                return {
                    'success': False,
                    'verified': False,
                    'error': 'Gagal memproses gambar',
                    'confidence': 0.0
                }
            
            # Deteksi wajah di gambar live
            face_locations = face_recognition.face_locations(rgb_image, model="hog")
            
            if len(face_locations) == 0:
                # Coba dengan model CNN jika HOG gagal
                face_locations = face_recognition.face_locations(rgb_image, model="cnn")
            
            if len(face_locations) == 0:
                return {
                    'success': False,
                    'verified': False,
                    'error': 'Tidak ada wajah terdeteksi dalam gambar live',
                    'confidence': 0.0
                }
            
            # Jika ada multiple wajah, ambil yang terbesar
            if len(face_locations) > 1:
                face_locations = [max(face_locations, key=lambda loc: (loc[2]-loc[0]) * (loc[1]-loc[3]))]
            
            # Generate encodings untuk wajah live
            live_encodings = face_recognition.face_encodings(rgb_image, face_locations, num_jitters=10, model="large")
            
            if len(live_encodings) == 0:
                return {
                    'success': False,
                    'verified': False,
                    'error': 'Gagal generate encoding untuk gambar live',
                    'confidence': 0.0
                }
            
            live_encoding = live_encodings[0]
            
            # Hitung distance dan confidence
            face_distance = face_recognition.face_distance([target_encoding], live_encoding)[0]
            confidence = (1 - face_distance) * 100  # Convert distance to confidence percentage
            
            # Verifikasi berdasarkan tolerance
            is_match = bool(face_distance <= self.tolerance)

            result = {
                'success': True,
                'verified': is_match,
                'confidence': round(float(confidence), 2),
                'distance': round(float(face_distance), 4),
                'threshold': float(self.tolerance),
                'user_id': user_id
            }
            
            if return_details:
                result.update({
                    'face_locations_detected': len(face_locations),
                    'live_face_location': face_locations[0],
                    'registered_image_path': self.known_metadata[user_index].get('image_path', 'Unknown')
                })
            
            logger.info(f"Verifikasi user {user_id}: {'✅ VERIFIED' if is_match else '❌ NOT VERIFIED'} "
                       f"(confidence: {confidence:.2f}%, distance: {face_distance:.4f})")
            
            return result
            
        except Exception as e:
            logger.error(f"Error verifying face: {str(e)}")
            return {
                'success': False,
                'verified': False,
                'error': str(e),
                'confidence': 0.0
            }
    
    def identify_face_from_base64(self, base64_image, top_matches=3):
        """
        Identifikasi wajah dari base64 string - cari user mana yang paling mirip
        """
        try:
            # Decode base64 image
            image_data = base64.b64decode(base64_image)
            
            with tempfile.NamedTemporaryFile(delete=False, suffix='.jpg') as temp_file:
                temp_file.write(image_data)
                temp_path = temp_file.name
            
            try:
                result = self.identify_face_from_file(temp_path, top_matches)
            finally:
                if os.path.exists(temp_path):
                    os.unlink(temp_path)
            
            return result
            
        except Exception as e:
            logger.error(f"Error identifying face from base64: {str(e)}")
            return {
                'success': False,
                'error': str(e),
                'matches': []
            }
    
    def identify_face_from_file(self, image_path, top_matches=3):
        """
        Identifikasi wajah dari file gambar - cari user yang paling mirip
        """
        try:
            if len(self.known_encodings) == 0:
                return {
                    'success': False,
                    'error': 'Tidak ada encodings yang tersimpan',
                    'matches': []
                }
            
            # Preprocess gambar
            rgb_image = self.preprocess_image(image_path)
            if rgb_image is None:
                return {
                    'success': False,
                    'error': 'Gagal memproses gambar',
                    'matches': []
                }
            
            # Deteksi wajah
            face_locations = face_recognition.face_locations(rgb_image, model="hog")
            
            if len(face_locations) == 0:
                face_locations = face_recognition.face_locations(rgb_image, model="cnn")
            
            if len(face_locations) == 0:
                return {
                    'success': False,
                    'error': 'Tidak ada wajah terdeteksi',
                    'matches': []
                }
            
            if len(face_locations) > 1:
                face_locations = [max(face_locations, key=lambda loc: (loc[2]-loc[0]) * (loc[1]-loc[3]))]
            
            # Generate encodings
            live_encodings = face_recognition.face_encodings(rgb_image, face_locations, num_jitters=10, model="large")
            
            if len(live_encodings) == 0:
                return {
                    'success': False,
                    'error': 'Gagal generate encoding',
                    'matches': []
                }
            
            live_encoding = live_encodings[0]
            
            # Hitung distance ke semua known faces
            distances = face_recognition.face_distance(self.known_encodings, live_encoding)
            
            # Buat list matches dengan confidence
            matches = []
            for i, distance in enumerate(distances):
                confidence = (1 - distance) * 100
                matches.append({
                    'user_id': self.known_names[i],
                    'confidence': round(float(confidence), 2),
                    'distance': round(float(distance), 4),
                    'is_match': bool(distance <= self.tolerance)
                })
            
            # Sort berdasarkan confidence (descending)
            matches.sort(key=lambda x: x['confidence'], reverse=True)
            
            # Ambil top matches
            top_matches_list = matches[:top_matches]
            
            return {
                'success': True,
                'matches': top_matches_list,
                'best_match': top_matches_list[0] if top_matches_list else None,
                'total_faces_in_db': len(self.known_encodings)
            }
            
        except Exception as e:
            logger.error(f"Error identifying face: {str(e)}")
            return {
                'success': False,
                'error': str(e),
                'matches': []
            }
    
    def get_user_info(self, user_id):
        """
        Dapatkan informasi metadata user
        """
        if user_id not in self.known_names:
            return None
        
        user_index = self.known_names.index(user_id)
        return self.known_metadata[user_index]


def main():
    parser = argparse.ArgumentParser(description='Face Recognition CLI')
    parser.add_argument('--encodings', default='encodings.pkl', help='Path ke file encodings')
    parser.add_argument('--tolerance', type=float, default=0.5, help='Face matching tolerance (0.0-1.0)')
    
    subparsers = parser.add_subparsers(dest='command', help='Commands')
    
    # Verify command
    verify_parser = subparsers.add_parser('verify', help='Verify face against specific user')
    verify_parser.add_argument('--image', required=True, help='Path ke gambar live')
    verify_parser.add_argument('--user-id', required=True, help='User ID untuk verifikasi')
    verify_parser.add_argument('--base64', action='store_true', help='Image input adalah base64 string')
    verify_parser.add_argument('--details', action='store_true', help='Return detailed results')
    
    # Identify command
    identify_parser = subparsers.add_parser('identify', help='Identify face from database')
    identify_parser.add_argument('--image', required=True, help='Path ke gambar live')
    identify_parser.add_argument('--base64', action='store_true', help='Image input adalah base64 string')
    identify_parser.add_argument('--top', type=int, default=3, help='Number of top matches to return')
    
    args = parser.parse_args()
    
    if not args.command:
        parser.print_help()
        return
    
    # Initialize recognizer
    recognizer = FaceRecognizer(args.encodings, args.tolerance)
    
    if args.command == 'verify':
        if args.base64:
            result = recognizer.verify_face_from_base64(args.image, args.user_id, args.details)
        else:
            result = recognizer.verify_face_from_file(args.image, args.user_id, args.details)
        
        print(json.dumps(result, indent=2))
        
    elif args.command == 'identify':
        if args.base64:
            result = recognizer.identify_face_from_base64(args.image, args.top)
        else:
            result = recognizer.identify_face_from_file(args.image, args.top)
        
        print(json.dumps(result, indent=2))


if __name__ == "__main__":
    main()