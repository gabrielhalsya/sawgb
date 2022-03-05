<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM alternatif WHERE nik='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$validasi = false; $err = false;
	if ($update) {
		$sql = "UPDATE alternatif SET nik='$_POST[nik]', nama='$_POST[nama]', alamat='$_POST[alamat]',jekkel='$_POST[jekkel]',umur='$_POST[umur]', profil_cv='$_POST[profil_cv]' WHERE nik='$_GET[key]'";
	} else {
		$sql = "INSERT INTO alternatif(nik,nama,alamat,kd_periodes,jekkel,umur,profil_cv) VALUES ('$_POST[nik]', '$_POST[nama]', '$_POST[alamat]' ,'$_POST[kd_periodes]','$_POST[jekkel]','$_POST[umur]','$_POST[profil_cv]')";
		$validasi = true;
	}

	if ($validasi) {
		$q = $connection->query("SELECT nik FROM alternatif WHERE nik=$_POST[nik]");
		if ($q->num_rows) {
			echo alert($_POST["nik"]." sudah terdaftar!", "?page=alternatif");
			$err = true;
		}
	}

  if (!$err AND $connection->query($sql)) {
    echo alert("Berhasil!", "?page=alternatif");
  } else {
		echo alert("Gagal!", "?page=alternatif");
  }
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM alternatif WHERE nik=$_GET[key]");
	echo alert("Berhasil!", "?page=alternatif");
}
?>
<div class="row">
	<div class="col-md-4">
	    <div class="panel panel-<?= ($update) ? "warning" : "info" ?>">
	        <div class="panel-heading"><h3 class="text-center"><?= ($update) ? "EDIT" : "TAMBAH" ?></h3></div>
	        <div class="panel-body">
	            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
				<div class="form-group">
						<label for="kd_periodes">Periode</label>
						<select class="form-control" name="kd_periodes">
							<option>---</option>
							<?php $query = $connection->query("SELECT * FROM periode WHERE status_periode='buka'"); while ($data = $query->fetch_assoc()): ?>
								<option value="<?=$data["kd_periode"]?>" <?= (!$update) ?: (($row["kd_periodes"] != $data["kd_periode"]) ?: 'selected="on"') ?>><?=$data["nama"]?></option>
							<?php endwhile; ?>
						</select>
					</div>
				
					<div class="form-group">
	                    <label for="nik">NIK</label>
	                    <input type="text" name="nik" class="form-control" <?= (!$update) ?: 'value="'.$row["nik"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="nama">Nama Lengkap</label>
	                    <input type="text" name="nama" class="form-control" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="alamat">Alamat</label>
	                    <input type="text" name="alamat" class="form-control" <?= (!$update) ?: 'value="'.$row["alamat"].'"' ?>>
	                </div>
					<div class="form-group">
						<label for="jekkel">Jenis Kelamin</label>
						<select class="form-control" name="jekkel">
							<option>---</option>
							<option value="Laki-laki" <?= (!$update) ?: (($row["jekkel"] != "Laki-laki") ?: 'selected="on"') ?>>Laki-laki</option>
							<option value="Perempuan" <?= (!$update) ?: (($row["jekkel"] != "Perempuan") ?: 'selected="on"') ?>>Perempuan</option>
						</select>
					</div>
					<div class="form-group">
	                    <label for="umur">Usia</label>
	                    <input type="text" name="umur" class="form-control" <?= (!$update) ?: 'value="'.$row["umur"].'"' ?>>
	                </div>
					<div class="form-group">
	                    <label for="profil_cv">Profil</label>
	                    <input type="text" name="profil_cv" class="form-control" <?= (!$update) ?: 'value="'.$row["profil_cv"].'"' ?>>
	                </div>
	                <button type="submit" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block">Simpan</button>
	                <?php if ($update): ?>
					<a href="?page=alternatif" class="btn btn-info btn-block">Batal</a>
					<?php endif; ?>
	            </form>
	        </div>
	    </div>
	</div>
	<div class="col-md-8">
	    <div class="panel panel-info">
	        <div class="panel-heading"><h3 class="text-center">DAFTAR Pelamar</h3></div>
	        <div class="panel-body">
	            <table class="table table-condensed">
	                <thead>
	                    <tr>
	                        <th>No</th>
	                        <th>NIK</th>
	                        <th>Nama</th>
	                        <th>Jenis Kelamin</th>
	                        <th>Usia</th>
	                        <th>Alamat</th>
	                        <th>Profil CV</th>
	                        <th>Periode</th>
	                        <th></th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php $no = 1; ?>
	                    <?php if ($query = $connection->query("SELECT m.*, n.nama as nama_periode FROM alternatif m,periode n WHERE n.kd_periode=m.kd_periodes")): ?>
	                        <?php while($row = $query->fetch_assoc()): ?>
	                        <tr>
	                            <td><?=$no++?></td>
	                            <td><?=$row['nik']?></td>
	                            <td><?=$row['nama']?></td>
	                            <td><?=$row['jekkel']?></td>
	                            <td><?=$row['umur']?></td>
	                            <td><?=$row['alamat']?></td>
	                            <td><?=$row['profil_cv']?></td>
	                            <td><?=$row['nama_periode']?></td>
	                            <td>
	                                <div class="btn-group">
	                                    <a href="?page=alternatif&action=update&key=<?=$row['nik']?>" class="btn btn-warning btn-xs">Edit</a>
	                                    <a href="?page=alternatif&action=delete&key=<?=$row['nik']?>" class="btn btn-danger btn-xs">Hapus</a>
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
