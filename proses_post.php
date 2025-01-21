<?php
// mengubungkan file konfigurasi database
include 'config.php';

// memulai sesi PHP
session_start();

// mendapatkan ID pengguna dari sesi
$userId = $_SESSION["user_id"];

// menangis form untuk menambahkan postingan baru
if (isset($_POST['simpan'])) {
    // mendapatkan data dari form
    $postTitle = $_POST["post_title"]; // judul postingan
    $content = $_POST["content"]; // konten postingan
    $categoryId = $_POST["category_id"]; // ID kategori

    // mengatur direktori penyimpanan file gambar
    $imageDir = "assets/img/uploads/";
    $imageName = $_FILES["image"]["name"]; // Nama file gambar
    $imagePath = $imageDir . basename($imageName); // path lengkap gambar

    // memindahkan file gambar yang diunggah ke direktori tujuan
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
        // jika unggahan berhasil, masukkan
        // data postingan ke dalam database
        $query = "INSERT INTO posts (post_title, content, created_at, category_id, user_id, image_path)
        VALUES ('$postTitle', '$content', NOW(), $categoryId, $userId,
        '$imagePath')";

        if ($conn->query($query) === TRUE) {
            // notifiksi berhasil jika postinga berhasil ditambahkan
            $_SESSION['notification'] = [
                'type' => 'primary',
                'message' => 'Post successfully added.'
            ];
        } else {
            // notifikasi error jika gagal menambakan postingan 
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Error adding post: ' . $conn->error
            ];
        }
    } else {
        // notifikasi error jika unggahan gambar gagal
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Failed to upload image.'
        ];
    }

    // arahkan ke halaman dashboard setelah selesai
    header('Location: dashboard.php');
    exit();
}