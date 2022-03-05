<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM nilai JOIN penilaian USING(kd_kriteria) WHERE kd_nilai='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST["save"])) {
	$validasi = false; $err = false;
	if ($update) {
		$sql = "UPDATE nilai SET kd_kriteria='$_POST[kd_kriteria]', nik='$_POST[nik]', nilai='$_POST[nilai]' WHERE kd_nilai='$_GET[key]'";
	} else {
		$query = "INSERT INTO nilai VALUES ";
		foreach ($_POST["nilai"] as $kd_kriteria => $nilai) {
			$query .= "(NULL, '$_POST[kd_periode]', '$kd_kriteria', '$_POST[nik]', '$nilai'),";
		}
		$sql = rtrim($query, ',');
		$validasi = true;
	}

	if ($validasi) {
		foreach ($_POST["nilai"] as $kd_kriteria => $nilai) {
			$q = $connection->query("SELECT kd_nilai FROM nilai WHERE kd_periode=$_POST[kd_periode] AND kd_kriteria=$kd_kriteria AND nik=$_POST[nik] AND nilai LIKE '%$nilai%'");
			if ($q->num_rows) {
				echo alert("Nilai untuk ".$_POST["nik"]." sudah ada!", "?page=nilai");
				$err = true;
			}
		}
	}

  if (!$err AND $connection->query($sql)) {
		echo alert("Berhasil!", "?page=nilai");
	} else {
		echo alert("Gagal!", "?page=nilai");
	}
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM nilai WHERE kd_nilai='$_GET[key]'");
	echo alert("Berhasil!", "?page=nilai");
}
?>
<div class="row">
	<div class="col-md-4">
	    <div class="panel panel-<?= ($update) ? "warning" : "info" ?>">
	        <div class="panel-heading"><h3 class="text-center"><?= ($update) ? "EDIT" : "TAMBAH" ?></h3></div>
	        <div class="panel-body">
				<form action="<?=$_SERVER["REQUEST_URI"]?>" method="post">
				<div class="form-group">
						<label for="kd_periode">periode</label>
						<?php if ($_POST): ?>
							<?php $q = $connection->query("SELECT nama FROM periode WHERE kd_periode=$_POST[kd_periode]"); ?>
							<input type="text"value="<?=$q->fetch_assoc()["nama"]?>" class="form-control" readonly="on">
							<input type="hidden" name="kd_periode" value="<?=$_POST["kd_periode"]?>">
						<?php else: ?>
							<select class="form-control" name="kd_periode" id="periode">
								<option>---</option>
								<?php $sql = $connection->query("SELECT * FROM periode where status_periode='buka'"); while ($data = $sql->fetch_assoc()): ?>
									<option value="<?=$data["kd_periode"]?>"<?= (!$update) ? "" : (($row["kd_periode"] != $data["kd_periode"]) ? "" : 'selected="selected"') ?>><?=$data["nama"]?></option>
								<?php endwhile; ?>
							</select>
						<?php endif; ?>
					</div>	
				<div class="form-group">
						<label for="nik">Pelamar</label>
						<?php if ($_POST): ?>
							<input type="text" name="nik" value="<?=$_POST["nik"]?>" class="form-control" readonly="on" id="alternatif">
						<?php else: ?>
							<!-- <select class="form-control" name="nik" name="alternatif" id="alternatif"> -->
							<select class="form-control" name="nik" id="alternatif">
								<option>---</option>
								<?php $sql = $connection->query("SELECT * FROM alternatif"); while ($data = $sql->fetch_assoc()): ?>
									<option value="<?=$data["nik"]?>" <?= (!$update) ? "" : (($row["nik"] != $data["nik"]) ? "" : 'selected="selected"') ?>><?=$data["nik"]?> | <?=$data["nama"]?></option>
								<?php endwhile; ?>
							</select>
						<?php endif; ?>
					</div>
					
					<?php if ($_POST): ?>
						<?php $q = $connection->query("SELECT * FROM kriteria WHERE kd_periode=$_POST[kd_periode]"); while ($r = $q->fetch_assoc()): ?>
				<div class="form-group">
					<label for="nilai"><?=ucfirst($r["nama"])?></label>
					<select class="form-control" name="nilai[<?=$r["kd_kriteria"]?>]" id="nilai">
						<option>---</option>
						<?php $sql = $connection->query("SELECT * FROM penilaian WHERE kd_kriteria=$r[kd_kriteria]"); while ($data = $sql->fetch_assoc()): ?>
							<option value="<?=$data["bobot"]?>" class="<?=$data["kd_kriteria"]?>"<?= (!$update) ? "" : (($row["kd_penilaian"] != $data["kd_penilaian"]) ? "" : ' selected="selected"') ?>><?=$data["keterangan"]?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<?php endwhile; ?>
				<input type="hidden" name="save" value="true">
				<?php endif; ?>
				<button type="submit" id="simpan" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block"><?=($_POST) ? "Simpan" : "Tampilkan"?></button>
				<?php if ($update): ?>
					<a href="?page=nilai" class="btn btn-info btn-block">Batal</a>
					<?php endif; ?>
	            </form>
	        </div>
	    </div>
	</div>
	<div class="col-md-8">
	    <div class="panel panel-info">
	        <div class="panel-heading"><h3 class="text-center">DAFTAR</h3></div>
	        <div class="panel-body">
	            <table class="table table-condensed">
	                <thead>
	                    <tr>
	                        <th>No</th>
							<th>nik</th>
							<th>Nama</th>
	                        <th>periode</th>
	                        <th>Kriteria</th>
	                        <th>Nilai</th>
	                        <th></th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php $no = 1; ?>
	                    <?php if ($query = $connection->query("SELECT a.kd_nilai, c.nama AS nama_periode, c.status_periode AS statusp,b.nama AS nama_kriteria, d.nik, d.nama AS nama_alternatif, a.nilai FROM nilai a JOIN kriteria b ON a.kd_kriteria=b.kd_kriteria JOIN periode c ON a.kd_periode=c.kd_periode JOIN alternatif d ON d.nik=a.nik WHERE c.status_periode='buka' ORDER BY c.kd_periode DESC")): ?>

	                    <?php //if ($query = $connection->query("SELECT a.kd_nilai, a.nilai,e.keterangan AS keterangan, c.nama AS nama_periode,b.nama AS nama_kriteria, d.nik, d.nama AS nama_alternatif FROM nilai a,kriteria b,periode c,alternatif d,penilaian e WHERE a.kd_kriteria=b.kd_kriteria AND b.kd_kriteria=e.kd_kriteria AND a.nilai=e.bobot ")): ?>
	                        <?php while($row = $query->fetch_assoc()): ?>
	                        <tr>
	                            <td><?=$no++?></td>
								<td><?=$row['nik']?></td>
								<td><?=$row['nama_alternatif']?></td>
	                            <td><?=$row['nama_periode']?></td>
	                            <td><?=$row['nama_kriteria']?></td>
	                            <td><?=$row['nilai']?></td>
	                            <!-- <td><?=$row['statusp']?></td> -->
	                            <!-- <td><?=$row['keterangan']?></td> -->
	                            <td>
	                                <div class="btn-group">
	                                    <a href="?page=nilai&action=update&key=<?=$row['kd_nilai']?>" class="btn btn-warning btn-xs">Edit</a>
	                                    <a href="?page=nilai&action=delete&key=<?=$row['kd_nilai']?>" class="btn btn-danger btn-xs">Hapus</a>
	                                </div>
	                            </td>
	                        </tr>
	                        <?php endwhile ?>
	                    <?php endif ?>
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>
</div>
<script type="text/javascript">
$("#kriteria").chained("#periode");
$("#nilai").chained("#kriteria");
</script>
