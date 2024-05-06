<?php
// Fungsi untuk mengenkripsi teks menggunakan metode Caesar Cipher
function encrypt($text, $shift) {
    $encrypted_text = "";
    $length = strlen($text);
    for ($i = 0; $i < $length; $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $ascii = ord($char);
            $offset = ($ascii - ord('a') + $shift) % 26;
            $encrypted_char = chr(ord('a') + $offset);
            $encrypted_text .= $encrypted_char;
        } else {
            $encrypted_text .= $char;
        }
    }
    return $encrypted_text;
}

// Fungsi untuk menghasilkan gambar QR-Code dari teks
function generateQRCode($text, $filename) {
    $url = urlencode($text);
    $api_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=$url";
    $image_data = file_get_contents($api_url);
    file_put_contents($filename, $image_data);
}

// Main program
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $document = $_POST['document'];
    $shift = $_POST['shift'];

    // Enkripsi dokumen
    $encrypted_document = encrypt($document, $shift);

    // Generate QR-Code
    $filename = "qr_code.png";
    generateQRCode($encrypted_document, $filename);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi Keamanan Data Dokumen</title>
</head>
<body>
    <h2>Keamanan Data Dokumen Menggunakan Algoritma Caesar Cipher berbasis QR-Code</h2>

    <form method="POST" enctype="multipart/form-data">
        <label for="document">Masukkan teks dokumen:</label><br>
        <textarea name="document" rows="5" cols="40" required></textarea><br><br>

        <label for="shift">Masukkan jumlah pergeseran (shift):</label>
        <input type="number" name="shift" min="1" max="25" required><br><br>

        <input type="submit" value="Enkripsi dan Generate QR-Code">
    </form>

    <?php if(isset($encrypted_document)): ?>
        <h3>Hasil Enkripsi:</h3>
        <p><?php echo $encrypted_document; ?></p>

        <?php if(file_exists($filename)): ?>
            <h3>Gambar QR-Code:</h3>
            <img src="<?php echo $filename; ?>" alt="QR-Code">
        <?php endif; ?>
    <?php endif; ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            /* display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; */
        }

        h2 {
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 0 auto;
            margin-top: 50px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        textarea, input[type="number"] {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
            margin-bottom: 10px;
            
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        h3 {
            color: #333;
            margin-top: 20px;  
            display: flex;
            justify-content: center;
            align-items: center;
        }

        p {
            font-size: 17px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        img {
            width: 300px;
            height: 300px;
            margin-top: 5px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
    
</body>
</html>
