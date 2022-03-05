<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM periode WHERE kd_periode='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$validasi = false; $err = false;
	if ($update) {
		$sql = "UPDATE periode SET nama='$_POST[nama]',status_periode='$_POST[status_periode]' WHERE kd_periode='$_GET[key]'";
	} else {
		$sql2 = "UPDATE periode SET status_periode='tutup' WHERE status_periode='buka'";
		$connection->query($sql2);
		$sql = "INSERT INTO periode VALUES (NULL, '$_POST[nama]', 'buka')";
		$validasi = true;
	}

	if ($validasi) {
		$q = $connection->query("SELECT kd_periode FROM periode WHERE nama LIKE '%$_POST[nama]%'");
		if ($q->num_rows) {
			echo alert("periode sudah ada!", "?page=periode");
			$err = true;
		}
	}

  if (!$err AND $connection->query($sql) ) {
    echo alert("Berhasil!", "?page=periode");
  } else {
		echo alert("Gagal!", "?page=periode");
  }
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM periode WHERE kd_periode='$_GET[key]'");
	echo alert("Berhasil!", "?page=periode");
}
?>
<div class="row">
	<div class="col-md-4">
	    <div class="panel panel-<?= ($update) ? "warning" : "info" ?>">
	        <div class="panel-heading"><h3 class="text-center"><?= ($update) ? "EDIT" : "TAMBAH" ?></h3></div>
	        <div class="panel-body">
	            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
	                <div class="form-group">
	                    <label for="nama">Nama</label>
	                    <input type="text" name="nama" class="form-control" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?>>
	                </div>
					<div class="form-group">
						<label for="status_periode">Status Periode</label>
						<select class="form-control" name="status_periode">
							<option>---</option>
							<option value="buka" <?= (!$update) ?: (($row["status_periode"] != "buka") ?: 'selected="on"') ?>>Buka</option>
							<option value="tutup" <?= (!$update) ?: (($row["status_periode"] != "tutup") ?: 'selected="on"') ?>>Tutup</option>
						</select>
					</div>
	                <button type="submit" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block">Simpan</button>
	                <?php if ($update): ?>
					<a href="?page=periode" class="btn btn-info btn-block">Batal</a>
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
	                        <th>Nama</th>
	                        <th>Status Periode</th>
	                        <th></th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php $no = 1; ?>
	                    <?php if ($query = $connection->query("SELECT * FROM periode")): ?>
	                        <?php while($row = $query->fetch_assoc()): ?>
	                        <tr>
	                            <td><?=$no++?></td>
	                            <td><?=$row['nama']?></td>
	                            <td><?=$row['status_periode']?></td>
	                            <td>
	                                <div class="btn-group">
	                                    <a href="?page=periode&action=update&key=<?=$row['kd_periode']?>" class="btn btn-warning btn-xs">Edit</a>
	                                    <a href="?page=periode&action=delete&key=<?=$row['kd_periode']?>" class="btn btn-danger btn-xs">Hapus</a>
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
