<!-- Read Data -->
<div class="mx-auto tampil">
    <div class="card">
        <div class="card-header text-white bg-secondary">
            Daftar Departemen
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Departemen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'koneksi.php';
                    $query2 = "SELECT id_departemen, nama_departemen
                                    FROM departemen
                                    ORDER BY id_departemen ASC";

                    $hasil2 = mysqli_query($koneksi, $query2);
                    $urut = 1;

                    while ($r2 = mysqli_fetch_array($hasil2)) {
                        $id = $r2['id_departemen'];
                        $departemen = $r2['nama_departemen'];
                        ?>
                        <tr>
                            <th scope="row"><?php echo $urut++; ?></th>
                            <td><?php echo $departemen; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>