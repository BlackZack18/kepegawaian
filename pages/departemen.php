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
$departemen = $sukses = $error = "";

// Edit
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM departemen WHERE id_departemen = ?";
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
    $query = "SELECT * FROM departemen WHERE id_departemen = '$id'";
    $hasil = mysqli_query($koneksi, $query);

    $r1 = mysqli_fetch_array($hasil);
    $departemen = $r1['nama_departemen'];

    if ($departemen == '') {
        $error = "Departemen tidak ditemukan";
    }

}

// Create
if (isset($_POST['simpan'])) {
    $departemen = $_POST['departemen'];

    // Cek apakah data sudah ada di database
    if ($departemen) {
        if ($op == 'edit') {
            // Update
            $query = "UPDATE departemen 
                      SET nama_departemen = '$departemen' 
                      WHERE id_departemen = '$id'";
            $hasil = mysqli_query($koneksi, $query);
            if ($hasil) {
                $sukses = "Data berhasil di update";
            } else {
                $error = "Data gagal di update";
            }
        } else {
            // Insert
            $query = "INSERT INTO departemen (nama_departemen) 
                      VALUES ('$departemen')";
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
            Tambah/Edit Data Departemen
        </div>
        <div class="card-body">
            <?php
            if ($error) {
                $_SESSION['success_message'] = $error;
                echo '<script>window.location.href = "index.php?page=departemen";</script>';
                exit();
            }
            
            if ($sukses) {
                $_SESSION['success_message'] = $sukses;
                echo '<script>window.location.href = "index.php?page=departemen";</script>';
                exit();
            }
            
            ?>
            <form action="" method="POST">
                <!-- nama departemen -->
                <div class="mb-3 row">
                    <label for="departemen" class="col-sm-2 col-form-label">Nama Departemen</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="departemen" name="departemen" maxlength="35" value="<?php echo $departemen; ?>">
                    </div>
                </div>
                <!-- button -->
                <div class="col-12">
                    <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    <button type="reset" class="btn btn-secondary"
                        onclick="window.location.href='index.php?page=departemen'">
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
            Data Departemen
        </div>
        <div class="card-body tabel-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Departemen</th>
                        <th scope="col" style="text-align: center;">Aksi</th>
                    </tr>
                <tbody>
                    <?php
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
                            <td scope="row" style="text-align: center;">
                                <a href="index.php?page=departemen&op=edit&id=<?php echo $id ?>">
                                    <button type="button" class="btn btn-warning">Edit</button>
                                </a>
                                <a href="index.php?page=departemen&op=delete&id=<?php echo $id ?>">
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