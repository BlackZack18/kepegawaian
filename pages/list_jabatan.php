<!-- Read Data -->
<div class="mx-auto tampil">
    <div class="card">
        <div class="card-header text-white bg-secondary">
            Daftar Jabatan
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Jabatan</th>
                        <th scope="col">Gaji</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'koneksi.php';
                    $query2 = "SELECT id_jabatan, nama_jabatan, gaji
                                    FROM jabatan
                                    ORDER BY id_jabatan ASC";

                    $hasil2 = mysqli_query($koneksi, $query2);
                    $urut = 1;

                    while ($r2 = mysqli_fetch_array($hasil2)) {
                        $id = $r2['id_jabatan'];
                        $jabatan = $r2['nama_jabatan'];
                        $gaji = $r2['gaji'];
                        ?>
                        <tr>
                            <th scope="row"><?php echo $urut++; ?></th>
                            <td><?php echo $jabatan; ?></td>
                            <td><?php echo "Rp " . number_format($gaji, 0, ',', '.'); ?></td>

                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>