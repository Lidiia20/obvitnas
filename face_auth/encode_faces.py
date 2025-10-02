#!/usr/bin/env python3
"""
Script untuk generate encoding wajah dari foto KTP
Simpan hasil encoding ke dalam pickle file untuk digunakan saat verifikasi
"""

import face_recognition
import pickle
import cv2
import os
import json
import numpy as np
from pathlib import Path
import argparse
import logging

# Setup logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

class FaceEncoder:
    def __init__(self, dataset_path="dataset", encodings_file="encodings.pkl"):
        self.dataset_path = Path(dataset_path)
        self.encodings_file = encodings_file
        self.known_encodings = []
        self.known_names = []
        self.known_metadata = []
        
    def preprocess_image(self, image_path):
        """
        Preprocessing gambar untuk meningkatkan akurasi deteksi
        """
        try:
            # Baca gambar
            image = cv2.imread(str(image_path))
            if image is None:
                logger.error(f"Tidak bisa membaca gambar: {image_path}")
                return None
            
            # Konversi ke RGB
            rgb_image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
            
            # Resize jika terlalu besar (optimasi performa)
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
            logger.error(f"Error preprocessing image {image_path}: {str(e)}")
            return None
    
    def encode_faces_from_dataset(self):
        """
        Generate encodings dari semua foto di folder dataset
        Struktur folder: dataset/user_id/foto_identitas.jpg
        """
        if not self.dataset_path.exists():
            logger.error(f"Dataset path tidak ditemukan: {self.dataset_path}")
            return False
        
        logger.info(f"Memproses dataset dari: {self.dataset_path}")
        
        # Reset data
        self.known_encodings = []
        self.known_names = []
        self.known_metadata = []
        
        processed_count = 0
        failed_count = 0
        
        # Loop melalui setiap folder user
        for user_folder in self.dataset_path.iterdir():
            if not user_folder.is_dir():
                continue
                
            user_id = user_folder.name
            logger.info(f"Memproses user: {user_id}")
            
            # Cari file foto identitas
            image_files = list(user_folder.glob("*.jpg")) + list(user_folder.glob("*.jpeg")) + list(user_folder.glob("*.png"))
            
            if not image_files:
                logger.warning(f"Tidak ada foto ditemukan untuk user {user_id}")
                failed_count += 1
                continue
            
            # Proses setiap foto (biasanya cuma 1 foto KTP per user)
            for image_file in image_files:
                try:
                    # Preprocess gambar
                    rgb_image = self.preprocess_image(image_file)
                    if rgb_image is None:
                        continue
                    
                    # Deteksi wajah
                    face_locations = face_recognition.face_locations(rgb_image, model="hog")
                    
                    if len(face_locations) == 0:
                        logger.warning(f"Tidak ada wajah terdeteksi di {image_file}")
                        # Coba dengan model CNN jika HOG gagal
                        face_locations = face_recognition.face_locations(rgb_image, model="cnn")
                    
                    if len(face_locations) == 0:
                        logger.error(f"Gagal mendeteksi wajah di {image_file}")
                        failed_count += 1
                        continue
                    
                    if len(face_locations) > 1:
                        logger.warning(f"Terdeteksi {len(face_locations)} wajah di {image_file}, menggunakan yang terbesar")
                        # Ambil wajah yang terbesar (area terluas)
                        face_locations = [max(face_locations, key=lambda loc: (loc[2]-loc[0]) * (loc[1]-loc[3]))]
                    
                    # Generate encodings
                    face_encodings = face_recognition.face_encodings(rgb_image, face_locations, num_jitters=10, model="large")
                    
                    if len(face_encodings) == 0:
                        logger.error(f"Gagal generate encoding untuk {image_file}")
                        failed_count += 1
                        continue
                    
                    # Simpan encoding dan metadata
                    self.known_encodings.append(face_encodings[0])
                    self.known_names.append(user_id)
                    self.known_metadata.append({
                        'user_id': user_id,
                        'image_path': str(image_file),
                        'face_location': face_locations[0],
                        'created_at': str(image_file.stat().st_mtime)
                    })
                    
                    processed_count += 1
                    logger.info(f"✅ Berhasil encode wajah untuk user {user_id}")
                    
                except Exception as e:
                    logger.error(f"Error memproses {image_file}: {str(e)}")
                    failed_count += 1
        
        logger.info(f"Selesai. Berhasil: {processed_count}, Gagal: {failed_count}")
        return processed_count > 0
    
    def encode_single_image(self, image_path, user_id):
        """
        Generate encoding untuk satu gambar spesifik
        """
        try:
            # Preprocess gambar
            rgb_image = self.preprocess_image(image_path)
            if rgb_image is None:
                return None
            
            # Deteksi wajah
            face_locations = face_recognition.face_locations(rgb_image, model="hog")
            
            if len(face_locations) == 0:
                # Coba dengan model CNN
                face_locations = face_recognition.face_locations(rgb_image, model="cnn")
            
            if len(face_locations) == 0:
                logger.error(f"Tidak ada wajah terdeteksi di {image_path}")
                return None
            
            if len(face_locations) > 1:
                # Ambil wajah terbesar
                face_locations = [max(face_locations, key=lambda loc: (loc[2]-loc[0]) * (loc[1]-loc[3]))]
            
            # Generate encoding
            face_encodings = face_recognition.face_encodings(rgb_image, face_locations, num_jitters=10, model="large")
            
            if len(face_encodings) == 0:
                logger.error(f"Gagal generate encoding untuk {image_path}")
                return None
            
            encoding_data = {
                'user_id': user_id,
                'encoding': face_encodings[0],
                'face_location': face_locations[0],
                'image_path': str(image_path)
            }
            
            return encoding_data
            
        except Exception as e:
            logger.error(f"Error encode single image {image_path}: {str(e)}")
            return None
    
    def save_encodings(self):
        """
        Simpan encodings ke file pickle
        """
        try:
            data = {
                'encodings': self.known_encodings,
                'names': self.known_names,
                'metadata': self.known_metadata
            }
            
            with open(self.encodings_file, 'wb') as f:
                pickle.dump(data, f)
            
            logger.info(f"✅ Encodings disimpan ke {self.encodings_file}")
            logger.info(f"Total encodings: {len(self.known_encodings)}")
            return True
            
        except Exception as e:
            logger.error(f"Error menyimpan encodings: {str(e)}")
            return False
    
    def load_encodings(self):
        """
        Load encodings dari file pickle
        """
        try:
            if not os.path.exists(self.encodings_file):
                logger.warning(f"File encodings tidak ditemukan: {self.encodings_file}")
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
    
    def add_user_encoding(self, image_path, user_id):
        """
        Tambah encoding user baru ke dataset yang sudah ada
        """
        # Load encodings yang sudah ada
        self.load_encodings()
        
        # Generate encoding untuk user baru
        encoding_data = self.encode_single_image(image_path, user_id)
        if encoding_data is None:
            return False
        
        # Cek apakah user sudah ada
        if user_id in self.known_names:
            # Update encoding yang sudah ada
            index = self.known_names.index(user_id)
            self.known_encodings[index] = encoding_data['encoding']
            self.known_metadata[index] = {
                'user_id': user_id,
                'image_path': encoding_data['image_path'],
                'face_location': encoding_data['face_location'],
                'updated_at': str(os.path.getmtime(image_path))
            }
            logger.info(f"✅ Updated encoding untuk user {user_id}")
        else:
            # Tambah encoding baru
            self.known_encodings.append(encoding_data['encoding'])
            self.known_names.append(user_id)
            self.known_metadata.append({
                'user_id': user_id,
                'image_path': encoding_data['image_path'],
                'face_location': encoding_data['face_location'],
                'created_at': str(os.path.getmtime(image_path))
            })
            logger.info(f"✅ Ditambahkan encoding untuk user {user_id}")
        
        # Simpan kembali
        return self.save_encodings()


def main():
    parser = argparse.ArgumentParser(description='Generate face encodings dari dataset')
    parser.add_argument('--dataset', default='dataset', help='Path ke folder dataset')
    parser.add_argument('--output', default='encodings.pkl', help='Output file untuk encodings')
    parser.add_argument('--single-image', help='Path ke single image untuk di-encode')
    parser.add_argument('--user-id', help='User ID untuk single image')
    
    args = parser.parse_args()
    
    encoder = FaceEncoder(args.dataset, args.output)
    
    if args.single_image and args.user_id:
        # Mode single image
        success = encoder.add_user_encoding(args.single_image, args.user_id)
        if success:
            print(f"✅ Berhasil menambahkan encoding untuk user {args.user_id}")
        else:
            print(f"❌ Gagal menambahkan encoding untuk user {args.user_id}")
    else:
        # Mode batch processing
        if encoder.encode_faces_from_dataset():
            encoder.save_encodings()
            print("✅ Proses encoding selesai")
        else:
            print("❌ Gagal melakukan encoding")


if __name__ == "__main__":
    main()