<?php 

require_once '../../../assets/db_connect.php';
//if form is submitted
if($_POST) {	

	$validator = array('success' => false, 'messages' => array());
	$idr=$_POST['idp'];
	
	$nis=strip_tags($connect->real_escape_string($_POST['nis']));
	$sql = "SELECT * FROM siswa WHERE id='$idr'";
	$usis = $connect->query($sql)->fetch_assoc();
	if(empty($nama)){
		$validator['success'] = false;
		$validator['messages'] = "Tidak Boleh Kosong Datanya!";
	}else{
			$sql = "update siswa set nis='$nis',nisn='$nisn',nama='$nama',jk='$jk',$agama='$agama',tempat='$tempat',tanggal='$tanggal',nik='$nik',alamat='$alamat',nama_ayah='$ayah',nama_ibu='$ibu' where id='$idr'";
			$query = $connect->query($sql);
			$validator['success'] = true;
			$validator['messages'] = "Data Siswa berhasil diperbaharui!";		
	};
	
	// close the database connection
	$connect->close();

	echo json_encode($validator);

}