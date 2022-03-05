        <script>
            window.print();
        </script>
<div class="container">
    <h2 style="text-align:center"> Laporan Penerimaaan Karyawan Baru</h2>
    <h4 style="text-align:center"> SPBU PT Kharen Solok</h4>
    <h5 style="text-align:center"> Jl Dt Parapatih Nan Sabatang, Solok</h5>
    <br>
    
	<h4>Data Pelamar Terdaftar</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Periode</th>
                <th>Nama Pelamar</th>
                <th>Jenis Kelamin</th>
                <th>Usia</th>
                <th>Asal</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php if ($query = $connection->query("SELECT alternatif.*, periode.nama as namam,periode.status_periode from alternatif,periode where alternatif.kd_periodes=periode.kd_periode GROUP BY alternatif.nik")): ?>
                <?php while($row = $query->fetch_assoc()): ?>
                <tr>
                    <td><?=$no++?></td>
                    <td><?=$row['namam']?></td>
                    <td><?=$row['nama']?>(<?=$row['nik']?>)</td>
                    <td><?=$row['jekkel']?></td>
                    <td><?=$row['umur']?> tahun</td>
                    <td><?=$row['alamat']?></td>
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