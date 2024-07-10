<?php
// Panggil autoload.php dari DOMPDF
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Buat objek DOMPDF baru
$dompdf = new Dompdf();

// Buat options untuk mengatur font
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);

// Buat koneksi ke database
$conn = new mysqli("localhost", "username", "password", "database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mendapatkan data dari database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Buat HTML dari data yang didapatkan
$html = '<html><body>';
$html .= '<h1>Data Pengguna</h1>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
            </tr>';

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $html .= '<tr>
                    <td>'.$row['id'].'</td>
                    <td>'.$row['nama'].'</td>
                    <td>'.$row['email'].'</td>
                  </tr>';
    }
} else {
    $html .= '<tr><td colspan="3">Tidak ada data yang ditemukan</td></tr>';
}

$html .= '</table>';
$html .= '</body></html>';

// Load HTML ke DOMPDF
$dompdf->loadHtml($html);

// Atur ukuran dan orientasi dokumen
$dompdf->setPaper('A4', 'portrait');

// Render PDF (pengolahan)
$dompdf->render();

// Simpan atau keluarkan (output) PDF ke file
$dompdf->stream("data_pengguna.pdf", array("Attachment" => false));
?>
