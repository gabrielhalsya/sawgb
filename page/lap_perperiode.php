<div class="row">
	<div class="col-md-12">
	    <div class="panel panel-info">
	        <div class="panel-heading"><h3 class="text-center">Laporan Nilai Per Pelamar</h3></div>
	        <div class="panel-body">
				<form class="form-inline" action="<?=$_SERVER["REQUEST_URI"]?>" method="post">
					<label for="mhs">Periode :</label>
					<select class="form-control" name="kd_periode">
						<option> --- </option>
						<?php $q = $connection->query("SELECT * FROM periode"); while ($r = $q->fetch_assoc()): ?>
							<option value="<?=$r["kd_periode"]?>"><?=$r["nama"]?></option>
						<?php endwhile; ?>
					</select>
					<button type="submit" class="btn btn-primary">Tampilkan</button>
				</form><br>
	            	<?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
						<div class="row">
							<div class="col-sm-4"></div>
							<div class="col-sm-4">
								<a href="?page=cetak_perperiode&key=<?=$_POST['kd_periode']?>" class="btn btn-primary btn-block btn-lg">Cetak</a>
							</div>
							<div class="col-sm-4"></div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<h3 class="mr-4">Data Pelamar</h3>
								<table class="table">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Pelamar</th>
											<th>Jenis Kelamin</th>
											<th>Usia</th>
											<th>Alamat</th>
											<th>Status</th>
											<th>skor akhir</th>
										</tr>	
										<tr>

										</tr>
									</thead>
									<tbody>
										<?php $no = 1; ?>
										<?php if ($query = $connection->query("SELECT * FROM hasil,alternatif WHERE hasil.nik=alternatif.nik AND hasil.kd_periode='$_POST[kd_periode]' ORDER BY hasil.nilai DESC;")): ?>
											<?php while($row = $query->fetch_assoc()): ?>
											<tr>
												<td><?=$no++?></td>
												<td><?=$row['nama']?><br>(<?=$row['nik']?>)</td>
												<td><?=$row['jekkel']?></td>
												<td><?=$row['umur']?></td>
												<td><?=$row['alamat']?></td>
												<td><?=$row['status']?></td> 
												<td><?=$row['nilai']?></td> 
											</tr>
											<?php endwhile ?>
										<?php endif ?>
									</tbody>
								</table>
							</div>
							<div class="col-sm-6">
								<h3 class="mr-4">Data Kriteria yang digunakan</h3>
								<table class="table table-condensed">
									<thead>
										<tr>
											<th>No</th>
											<th>Periode</th>
											<th>Kriteria</th>
											<th>Sifat</th>
											<th>Bobot</th>
										</tr>
									</thead>
									<tbody>
										<?php $no = 1; ?>
										<?php if ($query = $connection->query("SELECT a.nama AS kriteria, b.nama AS periode, a.kd_kriteria, a.sifat,c.bobot FROM kriteria a JOIN periode b USING(kd_periode) JOIN model c USING(kd_kriteria) WHERE a.kd_periode='$_POST[kd_periode]'")): ?>
											<?php while($row = $query->fetch_assoc()): ?>
											<tr>
												<td><?=$no++?></td>
												<td><?=$row['periode']?></td>
												<td><?=$row['kriteria']?></td>
												<td><?=$row['sifat']?></td>
												<td><?=$row['bobot']?></td>
											</tr>
											<?php endwhile ?>
										<?php endif ?>
									</tbody>
								</table>
							</div>
						</div>
						
					<?php endif; ?>
	        </div>
	    </div>
	</div>
</div>
