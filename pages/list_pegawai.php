<!-- Read Data -->
<div class="mx-auto tampil">
    <div class="card">
        <div class="card-header text-white bg-secondary">
            Daftar Pegawai
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">NIK</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">No. Hp</th>
                        <th scope="col">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'koneksi.php';
                    $query2 = "SELECT id_pegawai, nik, nama, jk, alamat, telepon, email
                                    FROM pegawai
                                    ORDER BY id_pegawai ASC";

                    $hasil2 = mysqli_query($koneksi, $query2);
                    $urut = 1;

                    while ($r2 = mysqli_fetch_array($hasil2)) {
                        $id = $r2['id_pegawai'];
                        $nik = $r2['nik'];
                        $nama = $r2['nama'];
                        $jk = $r2['jk'];
                        $alamat = $r2['alamat'];
                        $telepon = $r2['telepon'];
                        $email = $r2['email'];
                        ?>
                        <tr>
                            <th scope="row"><?php echo $urut++; ?></th>
                            <td><?php echo $nik; ?></td>
                            <td><?php echo $nama; ?></td>
                            <td>
                                <?php echo ($jk == 'L') ? 'Laki-Laki' : 'Perempuan'; ?>
                            </td>
                            <td><?php echo $alamat; ?></td>
                            <td><?php echo $telepon; ?></td>
                            <td><?php echo $email; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>