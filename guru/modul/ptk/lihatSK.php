<?php
function TanggalIndo($tanggal)
{
	$bulan = array ('Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split = explode('-', $tanggal);
	return $split[2] . ' ' . $bulan[ (int)$split[1]-1 ] . ' ' . $split[0];
};
include '../../../assets/db.php';
$idp=isset($_GET['idp']) ? $_GET['idp'] : $idku;
$sqsk=mysqli_query($koneksi, "select * from sk where ptk_id='$idp' order by tanggal_sk desc");
?>