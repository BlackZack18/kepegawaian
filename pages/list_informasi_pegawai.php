<div class="mx-auto tampil">
    <div class="card">
        <div class="card-header text-white bg-secondary">
            Daftar Informasi Pegawai
        </div>
        <div class="card-body tabel-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">NIK</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Departemen</th>
                        <th scope="col">Jabatan</th>
                    </tr>
                <tbody>
                    <?php
                    $query = "SELECT id, nik, nama, nama_departemen, nama_jabatan
                                FROM pegawai_departemen
                                JOIN pegawai USING(id_pegawai)
                                JOIN departemen USING(id_departemen)
                                JOIN jabatan USING(id_jabatan)";
                    $hasil = mysqli_query($koneksi, $query);
                    $urut = 1;

                    while ($r2 = mysqli_fetch_array($hasil)) {
                        $id = $r2['id'];
                        $nik = $r2['nik'];
                        $nama = $r2['nama'];
                        $nama_departemmen = $r2['nama_departemen'];
                        $jabatan = $r2['nama_jabatan'];
                        ?>
                        <tr>
                            <th scope="row"><?php echo $urut++; ?></th>
                            <td scope="row"><?php echo $nik; ?></td>
                            <td scope="row"><?php echo $nama; ?></td>
                            <td scope="row"><?php echo $nama_departemmen; ?></td>
                            <td scope="row"><?php echo $jabatan; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                </thead>
            </table>
        </div>
    </div>
</div>