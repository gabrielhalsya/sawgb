<div class="row">
	<div class="col-md-12">
		<form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
			<div class="form-group">
				<label for="kd_periode">Masukkan jumlah diterima pada periode ini</label>
				<input type="text" name="jml" class="form-control form-control-lg">
			</div>
			<div class="form-group">
			<button type="submit" class="btn btn-info btn-block">Tampil</button>
		</form><br><br>
	<?php 
	if (isset($_GET["periode"])  && $_SERVER["REQUEST_METHOD"] == "POST" ) {
		$sqlKriteria = "";
		$namaKriteria = [];
		$queryKriteria = $connection->query("SELECT a.kd_kriteria, a.nama FROM kriteria a JOIN model b USING(kd_kriteria) WHERE b.kd_periode=$_GET[periode]");
		while ($kr = $queryKriteria->fetch_assoc()) 
		{
			$sqlKriteria .= "SUM(
				IF(
					c.kd_kriteria=".$kr["kd_kriteria"].",
					IF(c.sifat='max', nilai.nilai/c.normalization, c.normalization/nilai.nilai), 0
				)
			) AS ".strtolower(str_replace(" ", "_", $kr["nama"])).",";
			$namaKriteria[] = strtolower(str_replace(" ", "_", $kr["nama"]));
		}
		$sql = "SELECT
			(SELECT nama FROM alternatif WHERE nik=mhs.nik) AS nama,
			(SELECT nik FROM alternatif WHERE nik=mhs.nik) AS nik,
			$sqlKriteria
			SUM(
				IF(
						c.sifat = 'max',
						nilai.nilai / c.normalization,
						c.normalization / nilai.nilai
				) * c.bobot
			) AS rangking
		FROM
			nilai
			JOIN alternatif mhs USING(nik)
			JOIN (
				SELECT
						nilai.kd_kriteria AS kd_kriteria,
						kriteria.sifat AS sifat,
						(
							SELECT bobot FROM model WHERE kd_kriteria=kriteria.kd_kriteria AND kd_periode=periode.kd_periode
						) AS bobot,
						ROUND(
							IF(kriteria.sifat='max', MAX(nilai.nilai), MIN(nilai.nilai)), 1
						) AS normalization
					FROM nilai
					JOIN kriteria USING(kd_kriteria)
					JOIN periode ON kriteria.kd_periode=periode.kd_periode
					WHERE periode.kd_periode=$_GET[periode]
				GROUP BY nilai.kd_kriteria
			) c USING(kd_kriteria)
		WHERE kd_periode=$_GET[periode]
		GROUP BY nilai.nik
		ORDER BY rangking DESC";?>

	  <div class="panel panel-info">
	      <div class="panel-heading"><h3 class="text-center"><h2 class="text-center"><?php $query = $connection->query("SELECT * FROM periode WHERE kd_periode=$_GET[periode]"); echo $query->fetch_assoc()["nama"]; ?></h2></h3></div>
	      <div class="panel-body">
	          <table class="table table-condensed table-hover">
	              <thead>
	                  <tr>
							<th>nik</th>
							<th>Nama</th>
							<th>Nilai</th>
							<th>Status</th>
	                  </tr>
	              </thead>
	              <tbody>
					<?php 
					$query = $connection->query($sql); 
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$ia=$_POST['jml'];	
					}else $ia='';
					$is=1;
					while($row = $query->fetch_assoc()): 
					?>
					<?php
					$rangking = number_format((float) $row["rangking"], 8, '.', '');
					$q = $connection->query("SELECT nik FROM hasil WHERE nik='$row[nik]' AND kd_periode='$_GET[periode]'");
					if (!$q->num_rows) {
						// $connection->query("INSERT INTO hasil VALUES(NULL,'10','13050511','22','sss')");
						$s = ($is<=$ia) ? "Diterima" : "Tidak diterima";
						$connection->query("INSERT INTO hasil VALUES(NULL, '$_GET[periode]', '$row[nik]', '$rangking','$s')");
					}
					?>

					<tr>
						<td><?=$row["nik"]?></td>
						<td><?=$row["nama"]?></td>
						<?php for($i=0; $i<count($namaKriteria); $i++): ?>
						<?php endfor ?>
						<td><?=$rangking?></td>
						<td><?php echo $s = ($is<=$ia) ? "Diterima" : "" ;?></td>
					</tr>
					<?php $is++;
					 endwhile;?>
	              </tbody>
	          </table>
	      </div>
	  </div>
	<?php } else { ?>
		<h1>Pilih berapa orang yang akan diterima</h1>
	<?php } ?>
	</div>
</div>
