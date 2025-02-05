<?php
// validasi user
if (!isset($_SESSION['role'])) {
    echo '<script>window.location.href = "login.php;</script>';
    exit();
}

if ($_SESSION['role'] != "admin") {
    echo '<script>window.location.href = "index.php";</script>';
    exit();
}

include 'koneksi.php';

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Variabel dan fungsi
$nama_jabatan = $gaji = $error = $sukses = "";

// Edit
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM jabatan WHERE id_jabatan = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $hasil = mysqli_stmt_execute($stmt);

    if ($hasil) {
        $sukses = "Data berhasil dihapus";
    } else {
        $error = "Gagal melakukan delete data";
    }
}


if ($op == 'edit') {
    $id = $_GET['id'];
    $query = "SELECT * FROM jabatan WHERE id_jabatan = '$id'";
    $hasil = mysqli_query($koneksi, $query);

    $r1 = mysqli_fetch_array($hasil);
    $jabatan = $r1['nama_jabatan'];
    $gaji = $r1['gaji'];

    if ($jabatan == '') {
        $error = "Jabatan tidak ditemukan";
    }

}

// Create
if (isset($_POST['simpan'])) {
    $jabatan = $_POST['nama_jabatan'];
    $gaji = $_POST['gaji'];

    // Cek apakah data sudah ada di database
    if ($jabatan && $gaji) {
        if ($op == 'edit') {
            // Update
            $query = "UPDATE jabatan 
                      SET nama_jabatan = '$jabatan', gaji = '$gaji' 
                      WHERE id_jabatan = '$id'";
            $hasil = mysqli_query($koneksi, $query);
            if ($hasil) {
                $sukses = "Data berhasil di update";
            } else {
                $error = "Data gagal di update";
            }
        } else {
            // Insert
            $query = "INSERT INTO jabatan (nama_jabatan, gaji) 
                      VALUES ('$jabatan', '$gaji')";
            $hasil = mysqli_query($koneksi, $query);
            if ($hasil) {
                $sukses = "Berhasil memasukkan data.";
            } else {
                $error = "Gagal memasukkan data.";
            }
        }

    } else {
        $error = "Silahkan Masukkan semua data.";
    }
}
?>
<!-- Edit Data -->
<div class="mx-auto tampil">
    <div class="card">
        <div class="card-header text-white bg-secondary">
            Tambah/Edit Data Jabatan
        </div>
        <div class="card-body">
            <?php
            if ($error) {
                $_SESSION['success_message'] = $error;
                echo '<script>window.location.href = "index.php?page=jabatan";</script>';
                exit();
            }
            
            if ($sukses) {
                $_SESSION['success_message'] = $sukses;
                echo '<script>window.location.href = "index.php?page=jabatan";</script>';
                exit();
            }
            
            ?>
            <form action="" method="POST">
                <!-- nama jabatan -->
                <div class="mb-3 row">
                    <label for="nama_jabatan" class="col-sm-2 col-form-label">Nama Jabatan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" maxlength="30" value="<?php echo $nama_jabatan; ?>">
                    </div>
                </div>
                <!-- gaji -->
                <div class="mb-3 row">
                    <label for="gaji" class="col-sm-2 col-form-label">Gaji (Rp.)</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="gaji" name="gaji" maxlength="8"
                            value="<?php echo $gaji; ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                </div>
                <!-- button -->
                <div class="col-12">
                    <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    <button type="reset" class="btn btn-secondary"
                        onclick="window.location.href='index.php?page=jabatan'">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Read Data -->
<div class="mx-auto tampil">
    <div class="card">
        <div class="card-header text-white bg-secondary">
            Data Jabatan
        </div>
        <div class="card-body tabel-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Gaji</th>
                        <th scope="col" style="text-align: center;">Aksi</th>
                    </tr>
                <tbody>
                    <?php
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
                            <td scope="row" style="text-align: center;">
                                <a href="index.php?page=jabatan&op=edit&id=<?php echo $id ?>">
                                    <button type="button" class="btn btn-warning">Edit</button>
                                </a>
                                <a href="index.php?page=jabatan&?op=delete&id=<?php echo $id ?>">
                                    <button type="button" class="btn btn-danger"
                                        onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</button>
                                </a>
                            </td>
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
<br>
<br>
<br>