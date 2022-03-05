<?php 
    if (isset($_GET['key'])) {
        ?>
        <script>
            window.print();
        </script>
<div class="container">
    <h2 style="text-align:center"> Laporan Penerimaaan Karyawan Baru</h2>
    <h4 style="text-align:center"> SPBU PT Kharen Solok</h4>
    <h5 style="text-align:center"> Jl Dt Parapatih Nan Sabatang, Pandan Solok</h5>
    <br>
    <h4>Data Pelamar</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelamar</th>
                <th>Alamat</th>
                <th>Status</th>
                <th>skor akhir</th>
            </tr>	
            <tr>
    
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php if ($query = $connection->query("SELECT * FROM hasil,alternatif WHERE hasil.nik=alternatif.nik AND hasil.kd_periode='$_GET[key]' ORDER BY hasil.nilai DESC;")): ?>
                <?php while($row = $query->fetch_assoc()): ?>
                <tr>
                    <td><?=$no++?></td>
                    <td><?=$row['nama']?> (<?=$row['nik']?>)</td>
                    <td><?=$row['alamat']?></td>
                    <td><?=$row['status']?></td> 
                    <td><?=$row['nilai']?></td> 
                </tr>
                <?php endwhile ?>
            <?php endif ?>
        </tbody>
    </table><br>
    <h4>Data Kriteria yang digunakan</h4>
    <table class="table table-bordered">
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
            <?php if ($query = $connection->query("SELECT a.nama AS kriteria, b.nama AS periode, a.kd_kriteria, a.sifat,c.bobot FROM kriteria a JOIN periode b USING(kd_periode) JOIN model c USING(kd_kriteria) WHERE a.kd_periode='$_GET[key]'")): ?>
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
        </div><br>
        <?php
             $bulan = array(
                '01' => 'JANUARI',
                '02' => 'FEBRUARI',
                '03' => 'MARET',
                '04' => 'APRIL',
                '05' => 'MEI',
                '06' => 'JUNI',
                '07' => 'JULI',
                '08' => 'AGUSTUS',
                '09' => 'SEPTEMBER',
                '10' => 'OKTOBER',
                '11' => 'NOVEMBER',
                '12' => 'DESEMBER',
        );

        ?>
        <h5 style="text-align: right;"> Solok,  <?php echo date('d').' '.(ucwords(strtolower($bulan[date('m')]))).' '.date('Y') ?></h5>
        <br><br>
        <h5 style="text-align: right;"> Manager SPBU PT Kharen </h5>
<?php }?>