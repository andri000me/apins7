<?php include "partial/head.php"; ?>
</head>
<body>
<?php
$tgl=isset($_GET['tanggal']) ? $_GET['tanggal'] : date("d");
$bln=isset($_GET['bulan']) ? $_GET['bulan'] : date("m");
$thn=isset($_GET['tahun']) ? $_GET['tahun'] : date("Y");
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
$tanggal1 = $thn."-".$bln."-".$tgl;
	$day = date('D', strtotime($tanggal1));
	$dayList = array(
		'Sun' => 'Minggu',
		'Mon' => 'Senin',
		'Tue' => 'Selasa',
		'Wed' => 'Rabu',
		'Thu' => 'Kamis',
		'Fri' => 'Jumat',
		'Sat' => 'Sabtu'
	);
$idpegs = mysqli_fetch_array(mysqli_query($koneksi, "select * from id_pegawai where ptk_id='$idku'"));
$idpeg=$idpegs['pegawai_id'];
$absen = mysqli_query($koneksi, "SELECT pegawai_id,
DATE_FORMAT(tanggal,'%Y-%m-%d') tgl,
min(left(RIGHT(tanggal, 8), 5)) jam1,
MAX(left(right(tanggal, 8), 5)) jam2,
if(LEAST(12600,trim(TIME_TO_SEC(TIMEDIFF(min(left(RIGHT(tanggal, 8), 5)), '07:00:00'))))>0,LEAST(12600,trim(TIME_TO_SEC(TIMEDIFF(min(left(RIGHT(tanggal, 8), 5)), '07:00:00'))))/60,'') diff1, 
if(LEAST(14400,trim(TIME_TO_SEC(TIMEDIFF('16:00:00', MAX(left(right(tanggal, 8), 5))))))>0,LEAST(14400,trim(TIME_TO_SEC(TIMEDIFF('16:00:00', MAX(left(right(tanggal, 8), 5))))))/60,'') diff2
FROM absensi_ptk where pegawai_id='$idpeg' and DATE_FORMAT(tanggal,'%Y-%m-%d') = '$tanggal1' group by tgl");
$nk=mysqli_fetch_array($absen);
if($level==98 or $level==97){
				$sq2=mysqli_query($koneksi, "select * from penempatan JOIN siswa USING(peserta_didik_id) where siswa.jk='L' and penempatan.rombel='$kelas' and penempatan.tapel='$tpl_aktif'");
				$sq3=mysqli_query($koneksi, "select * from penempatan JOIN siswa USING(peserta_didik_id) where siswa.jk='P' and penempatan.rombel='$kelas' and penempatan.tapel='$tpl_aktif'");
				$juml=mysqli_num_rows($sq2);
				$jump=mysqli_num_rows($sq3);
				$jtot=mysqli_query($koneksi, "select * from siswa where status=1");
				$jjum=mysqli_num_rows($jtot);
				$jper=mysqli_query($koneksi, "select * from siswa where jk='P' and status=1");
				$jjper=mysqli_num_rows($jper);
				$jlak=mysqli_query($koneksi, "select * from siswa where jk='L' and status=1");
				$jjlak=mysqli_num_rows($jlak);
				
				$total=$juml+$jump;
				if($total>0){
					$perlak=($juml/$total)*100;
					$perper=($jump/$total)*100;
				}else{
					$perlak=0;
					$perper=0;
				};
				
				$sabsen=mysqli_query($koneksi, "select * from penempatan where rombel='$kelas' and tapel='$tpl_aktif'");
				$sakit=0;
				$ijin=0;
				$alfa=0;
				while($mk=mysqli_fetch_array($sabsen)){
					$pds=$mk['peserta_didik_id'];
					$hari = cal_days_in_month(CAL_GREGORIAN, $bln, $thn);
					for ($i=1; $i < $hari+1; $i++) { 
						if($i>9){
							$ac=$i;
						}else{
							$ac="0".$i;
						};
						$ttt=$thn."-".$bln."-".$ac;
						$snama=mysqli_query($koneksi, "select *,SUM(IF(absensi='S',1,0)) AS sakit,SUM(IF(absensi='I',1,0)) AS ijin,SUM(IF(absensi='A',1,0)) AS alfa from absensi where tanggal='$ttt' and peserta_didik_id='$pds'");
						$jab=mysqli_fetch_array($snama);
						$sakit=$sakit+$jab['sakit'];
						$ijin=$ijin+$jab['ijin'];
						$alfa=$alfa+$jab['alfa'];
					};
				};
				$jkeh=$sakit+$ijin+$alfa;
				$hef=mysqli_query($koneksi,"select * from hari_efektif where bulan='$bln' and tapel='$tpl_aktif'");
				$cektanggal=mysqli_num_rows($hef);
				if($cektanggal>0){
					$efek=mysqli_fetch_array($hef);
					if($efek['hari']==0){
						$harim=23;
					}else{
						$harim=$efek['hari'];
					};
				}else{
					$harim=23;
				};
				$hefek=round(($jkeh*100)/($harim*$total),2);
				
				}else{
				$sqlsisw = mysqli_query($koneksi, "siswa where status=1");
				$sq2=mysqli_query($koneksi, "select * from siswa where status=1 and jk='L'");
				$sq3=mysqli_query($koneksi, "select * from siswa where status=1 and jk='P'");
				$juml=mysqli_num_rows($sq2);
				$jump=mysqli_num_rows($sq3);
				
				$total=$juml+$jump;
				if($total>0){
					$perlak=($juml/$total)*100;
					$perper=($jump/$total)*100;
				}else{
					$perlak=0;
					$perper=0;
				};	
				};
?>
	<div class="wrapper overlay-sidebar">
		<?php include "partial/main-header.php"; ?>

		<!-- Sidebar -->
		<?php include "partial/sidebar.php"; ?>
		<!-- End Sidebar -->

		<div class="main-panel">
			<div class="content">
				<div class="panel-header bg-primary-gradient">
					<div class="page-inner py-5">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold">Beranda</h2>
								<h5 class="text-white op-7 mb-2">Selamat Datang <?=$bioku['nama'];?></h5>
							</div>
							<!--
							<div class="ml-md-auto py-2 py-md-0">
								<a href="#" class="btn btn-white btn-border btn-round mr-2">Manage</a>
								<a href="#" class="btn btn-secondary btn-round">Add Customer</a>
							</div>
							-->
						</div>
					</div>
				</div>
				<div class="page-inner mt--5">
					<div class="row">
						<div class="col-md-3">
							<div class="card card-profile">
								<div class="card-header" style="background-image: url('../assets/img/blogpost.jpg')">
									<div class="profile-picture">
										<div class="avatar avatar-xl">
											<img src="<?=$avatar;?>" alt="..." class="avatar-img rounded-circle">
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="user-profile text-center">
										<div class="name"><?=$bioku['nama'];?></div>
										<div class="job"><?=$jns_ptk['jenis_ptk'];?></div>
										<div class="desc"><?=$bioku['email'];?></div>
										<div class="social-media">
											<a class="btn btn-info btn-twitter btn-sm btn-link" href="#"> 
												<span class="btn-label just-icon"><i class="flaticon-twitter"></i> </span>
											</a>
											<a class="btn btn-danger btn-sm btn-link" rel="publisher" href="#"> 
												<span class="btn-label just-icon"><i class="flaticon-google-plus"></i> </span> 
											</a>
											<a class="btn btn-primary btn-sm btn-link" rel="publisher" href="#"> 
												<span class="btn-label just-icon"><i class="flaticon-facebook"></i> </span> 
											</a>
											<a class="btn btn-danger btn-sm btn-link" rel="publisher" href="#"> 
												<span class="btn-label just-icon"><i class="flaticon-dribbble"></i> </span> 
											</a>
										</div>
										<div class="view-profile">
											<a href="profil.php" class="btn btn-secondary btn-block">View Full Profile</a>
										</div>
									</div>
								</div>
								<div class="card-footer">
									<div class="row user-stats text-center">
										
										<div class="col">
											<div class="number"><?php if($kelas==0){echo "Semua";}else{echo $kelas;} ?></div>
											<div class="title">Kelas</div>
										</div>
										<div class="col">
											<div class="number"><?=$juml;?></div>
											<div class="title">Laki-laki</div>
										</div>
										<div class="col">
											<div class="number"><?=$jump;?></div>
											<div class="title">Perempuan</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-9">
							<?php if($level==98 or $level==97){ ?>
							<div class="card full-height">
								<div class="card-header">
									<div class="card-head-row">
										<div class="card-title">Data Absensi Kelas <?=$kelas;?></div>
										<div class="card-tools">
											<ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm" id="pills-tab" role="tablist">
												<li class="nav-item">
													<input type="hidden" name="kelas" id="kelas" class="form-control" value="<?=$kelas;?>" placeholder="Username">
											<input type="hidden" name="tapel" id="tapel" class="form-control" value="<?=$tapel;?>" placeholder="Username">
											<div class="form-group form-group-default">
													<label>Bulan</label>
													<select class="form-control" name="bulan" id="bulan">
														<option value="07" <?php if($bln==="07"){echo "selected";}; ?>>Juli</option>
														<option value="08" <?php if($bln==="08"){echo "selected";}; ?>>Agustus</option>
														<option value="09" <?php if($bln==="09"){echo "selected";}; ?>>September</option>
														<option value="10" <?php if($bln==="10"){echo "selected";}; ?>>Oktober</option>
														<option value="11" <?php if($bln==="11"){echo "selected";}; ?>>November</option>
														<option value="12" <?php if($bln==="12"){echo "selected";}; ?>>Desember</option>
														<option value="01" <?php if($bln==="01"){echo "selected";}; ?>>Januari</option>
														<option value="02" <?php if($bln==="02"){echo "selected";}; ?>>Februari</option>
														<option value="03" <?php if($bln==="03"){echo "selected";}; ?>>Maret</option>
														<option value="04" <?php if($bln==="04"){echo "selected";}; ?>>April</option>
														<option value="05" <?php if($bln==="05"){echo "selected";}; ?>>Mei</option>
														<option value="06" <?php if($bln==="06"){echo "selected";}; ?>>Juni</option>
													</select>
											</div>
											
												</li>
												<li class="nav-item">
													<div class="form-group form-group-default">
													<label>Tahun</label>
													<select class="form-control" name="tahun" id="tahun">
														<option value="2018" <?php if($thn==="2018"){echo "selected";}; ?>>2018</option>
														<option value="2019" <?php if($thn==="2019"){echo "selected";}; ?>>2019</option>
														<option value="2020" <?php if($thn==="2020"){echo "selected";}; ?>>2020</option>
														<option value="2021" <?php if($thn==="2021"){echo "selected";}; ?>>2021</option>
														<option value="2022" <?php if($thn==="2022"){echo "selected";}; ?>>2022</option>
														<option value="2023" <?php if($thn==="2023"){echo "selected";}; ?>>2023</option>
														<option value="2024" <?php if($thn==="2024"){echo "selected";}; ?>>2024</option>
														<option value="2025" <?php if($thn==="2025"){echo "selected";}; ?>>2025</option>
														<option value="2026" <?php if($thn==="2026"){echo "selected";}; ?>>2026</option>
													</select>
											</div>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div id="dataAbsen"></div>
								</div>
							</div>
							<?php }else{ ?>
							<div class="card full-height">
								<div class="card-header">
									<div class="card-head-row">
										<div class="card-title">Info Guru Mata Pelajaran</div>
									</div>
								</div>
								<div class="card-body">
									Beranda masih dalam tahap uji coba.
								</div>
							</div>
							<?php } ?>
						</div>
						
					</div>
					
					
				</div>
			</div>
			<?php include "partial/footer.php"; ?>
		</div>
		
		<!-- Custom template | don't include it in your project! -->
		<!-- End Custom template -->
	</div>
	<?php include "partial/foot.php"; ?>
	<script type="text/javascript" language="javascript" class="init">
	$(document).ready(function() {
		viewTr();
		function viewTr(){
			var bulan = $('#bulan').val();
			var tahun=$('#tahun').val();
			var tapel=$('#tapel').val();
			var kelas=$('#kelas').val();
			
			$.ajax({
				type : 'GET',
				url : 'dataAbsen.php',
				data :  'bulan=' + bulan+'&tahun='+tahun+'&tapel='+tapel+'&kelas='+kelas,
				beforeSend: function()
				{	
					$("#dataAbsen").html('<div class="alert alert-info alert-dismissible"><h4><i class="fa fa-spinner fa-pulse fa-fw"></i> Memuat Data Absensi Kelas....</h4></div>');
				},
				success: function (data) {

					//jika data berhasil didapatkan, tampilkan ke dalam option select mp
					$("#dataAbsen").html(data);
				}
			});
		};
		$('#bulan').change(function(){
			//Mengambil value dari option select mp kemudian parameternya dikirim menggunakan ajax
			var bulan = $('#bulan').val();
			var tahun=$('#tahun').val();
			var tapel=$('#tapel').val();
			var kelas=$('#kelas').val();
			
			$.ajax({
				type : 'GET',
				url : 'dataAbsen.php',
				data :  'bulan=' + bulan+'&tahun='+tahun+'&tapel='+tapel+'&kelas='+kelas,
				beforeSend: function()
				{	
					$("#dataAbsen").html('<div class="alert alert-info alert-dismissible"><h4><i class="fa fa-spinner fa-pulse fa-fw"></i> Memuat Data Absensi Kelas....</h4></div>');
				},
				success: function (data) {

					//jika data berhasil didapatkan, tampilkan ke dalam option select mp
					$("#dataAbsen").html(data);
				}
			});
		});
		$('#tahun').change(function(){
			//Mengambil value dari option select mp kemudian parameternya dikirim menggunakan ajax
			var bulan = $('#bulan').val();
			var tahun=$('#tahun').val();
			var tapel=$('#tapel').val();
			var kelas=$('#kelas').val();
			
			$.ajax({
				type : 'GET',
				url : 'dataAbsen.php',
				data :  'bulan=' + bulan+'&tahun='+tahun+'&tapel='+tapel+'&kelas='+kelas,
				beforeSend: function()
				{	
					$("#dataAbsen").html('<div class="alert alert-info alert-dismissible"><h4><i class="fa fa-spinner fa-pulse fa-fw"></i> Memuat Data Absensi Kelas....</h4></div>');
				},
				success: function (data) {

					//jika data berhasil didapatkan, tampilkan ke dalam option select mp
					$("#dataAbsen").html(data);
				}
			});
		});
	});
</script>
	<script>
		$.notify({
			icon: 'flaticon-alarm-1',
			title: 'APINS',
			message: 'Versi : 7.0.1',
		},{
			type: 'info',
			placement: {
				from: "bottom",
				align: "right"
			},
			time: 1000,
		});
	</script>
</body>
</html>