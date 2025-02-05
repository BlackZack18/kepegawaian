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
$nik = $nama = $jk = $alamat = $telepon = $email = $jabatan = $sukses = $error = "";

// get param op
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM pegawai WHERE id_pegawai = ?";
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
    $query = "SELECT * FROM pegawai WHERE id_pegawai = '$id'";
    $hasil = mysqli_query($koneksi, $query);

    $r1 = mysqli_fetch_array($hasil);
    $nik = $r1['nik'];
    $nama = $r1['nama'];
    $jk = $r1['jk'];
    $alamat = $r1['alamat'];
    $telepon = $r1['telepon'];
    $email = $r1['email'];
    $jabatan = $r1['id_jabatan'];

    if ($nik == '') {
        $error = "NIK tidak ditemukan";
    }

}

// Create
if (isset($_POST['simpan'])) {
    $nik_value = $_POST['nik'];
    $nama = $_POST['nama'];
    $jk = $_POST['jk'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    $jabatan = $_POST['id_jabatan'];

    // Cek apakah data sudah ada di database
    if ($nik_value && $nama && $jk && $alamat && $telepon && $email && $jabatan) {
        if ($op == 'edit') {
            // Update
            $query = "UPDATE pegawai 
                      SET nik = '$nik_value', nama = '$nama', jk = '$jk', alamat = '$alamat', telepon = '$telepon', email = '$email', id_jabatan =  '$jabatan' 
                      WHERE id_pegawai = '$id'";
            $hasil = mysqli_query($koneksi, $query);
            if ($hasil) {
                $sukses = "Data berhasil di update";
            } else {
                $error = "Data gagal di update";
            }
        } else {
            // Insert
            $query1 = "INSERT INTO pegawai (nik, nama, jk, alamat, telepon, email, id_jabatan) 
                      VALUES ('$nik_value', '$nama', '$jk', '$alamat', '$telepon', '$email', '$jabatan')";
            $hasil1 = mysqli_query($koneksi, $query1);
            if ($hasil1) {
                $sukses = "Berhasil memasukkan data.";
            } else {
                $error = "Gagal memasukkan data.";
            }
        }

    } else {
        $error = "Silahkan Masukkan semua data.";
    }
}

// Ambil NIK terakhir
$query_nik = "SELECT nik FROM pegawai ORDER BY id_pegawai DESC LIMIT 1";
$hasil_nik = mysqli_query($koneksi, $query_nik);
$row_nik = mysqli_fetch_assoc($hasil_nik);

// Jika ada data sebelumnya, tambahkan 1 pada NIK terakhir
if ($row_nik) {
    $nik_terakhir = intval($row_nik['nik']) + 1;
} else {
    $nik_terakhir = 2025001; // Set default jika belum ada data
}

// Jika mode edit, gunakan NIK yang ada, jika tidak, gunakan yang baru
if ($op == 'edit') {
    $nik_value = $nik;
} else {
    $nik_value = $nik_terakhir;
}
?>

<!-- Insert Data -->
<div class="mx-auto tampil">
    <div class="card">
        <div class="card-header text-white bg-secondary">
            Tambah/Edit Data Pegawai
        </div>
        <div class="card-body">
            <?php
            if ($error) {
                $_SESSION['error_message'] = $error;
                echo '<script>window.location.href = "index.php?page=pegawai";</script>';
                exit();
            } else if ($sukses) {
                $_SESSION['success_message'] = $sukses;
                echo '<script>window.location.href = "index.php?page=pegawai";</script>';
                exit();
            }
            ?>
            <form action="" method="POST">
                <!-- nik -->
                <div class="mb-3 row">
                    <label for="nik" class="col-sm-2 col-form-label">NIK</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nik" name="nik" value="<?php echo $nik_value; ?>"
                            readonly>
                    </div>
                </div>
                <!-- nama -->
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>">
                    </div>
                </div>
                <!-- jk -->
                <div class="mb-3 row">
                    <label for="jk" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="jk" id="jk">
                            <option selected>-Jenis Kelamin-</option>
                            <option value="L" <?php if ($jk == 'L')
                                echo 'selected'; ?>>Laki-laki
                            </option>
                            <option value="P" <?php if ($jk == 'P')
                                echo 'selected'; ?>>Perempuan
                            </option>
                        </select>
                    </div>
                </div>
                <!-- alamat -->
                <div class="mb-3 row">
                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="alamat" name="alamat"
                            value="<?php echo $alamat; ?>">
                    </div>
                </div>
                <!-- telepon -->
                <div class="mb-3 row">
                    <label for="telepon" class="col-sm-2 col-form-label">No. Hp</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="telepon" name="telepon" maxlength="13"
                            value="<?php echo $telepon; ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                </div>
                <!-- email -->
                <div class="mb-3 row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                    </div>
                </div>
                <!-- jabatan -->
                <div class="mb-3 row">
                    <label for="id_jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                    <div class="col-sm-10">

                        <?php
                        $query_jabatan = "SELECT id_jabatan, nama_jabatan FROM jabatan";
                        $hasil = mysqli_query($koneksi, $query_jabatan);
                        ?>
                        <select class="form-control" name="id_jabatan" id="id_jabatan">
                            <option selected>-Pilih Jabatan-</option>
                            <?php
                            while ($row = $hasil->fetch_assoc()) {
                                $selected = ($jabatan == $row['id_jabatan']) ? 'selected' : '';
                                echo "<option value='{$row['id_jabatan']}' $selected>{$row['nama_jabatan']}</option>";
                            }
                            ?>

                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    <button type="reset" class="btn btn-secondary"
                        onclick="window.location.href='index.php?page=pegawai'">
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
            Data Pegawai
        </div>
        <div class="card-body tabel-container">
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
                        <th scope="col">Jabatan</th>
                        <th scope="col" style="text-align: center;">Aksi</th>
                    </tr>
                <tbody>
                    <?php
                    $query2 = "SELECT id_pegawai, nik, nama, jk, alamat, telepon, email, nama_jabatan 
                                    FROM pegawai 
                                    INNER JOIN jabatan USING(id_jabatan) 
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
                        $jabatan = $r2['nama_jabatan'];
                        ?>
                        <tr>
                            <th scope="row"><?php echo $urut++; ?></th>
                            <td scope="row"><?php echo $nik; ?></td>
                            <td scope="row"><?php echo $nama; ?></td>
                            <td scope="row">
                                <?php
                                if ($jk == 'L') {
                                    echo 'Laki-Laki';
                                } elseif ($jk == 'P') {
                                    echo 'Perempuan';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td scope="row"><?php echo $alamat; ?></td>
                            <td scope="row"><?php echo $telepon; ?></td>
                            <td scope="row"><?php echo $email; ?></td>
                            <td scope="row"><?php echo $jabatan; ?></td>
                            <td scope="row" style="text-align: center;">
                                <a href="index.php?page=pegawai&op=edit&id=<?php echo $id ?>">
                                    <button type="button" class="btn btn-warning">Edit</button>
                                </a>
                                <a href="index.php?page=pegawai&op=delete&id=<?php echo $id ?>">
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