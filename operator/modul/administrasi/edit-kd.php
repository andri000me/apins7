<?php
include("../../../assets/db_connect.php");
$idr=$_POST['rowid'];
$utt=$connect->query("SELECT * FROM kd WHERE id_kd='$idr'")->fetch_assoc();
$idmp=$utt['mapel'];
?>
						<div class="modal-body">