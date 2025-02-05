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

$id_pegawai = $id_departemen = "";
$error = $sukses = "";

// get param op
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// Cek mode edit
if ($op == 'edit') {
    $id = $_GET['id'];
    $query = "SELECT * FROM pegawai_departemen WHERE id = '$id'";
    $hasil = mysqli_query($koneksi, $query);
    $r1 = mysqli_fetch_array($hasil);

    $id_pegawai = $r1['id_pegawai'];
    $id_departemen = $r1['id_departemen'];

    if (!$id_pegawai) {
        $error = "Data tidak ditemukan!";
    }
}

// delete
if ($op == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM pegawai_departemen WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $hasil = mysqli_stmt_execute($stmt);

    if ($hasil) {
        $sukses = "Data berhasil dihapus";
    } else {
        $error = "Gagal melakukan delete data";
    }
}

// Proses Create & Update
if (isset($_POST['simpan'])) {
    $id_pegawai = $_POST['id_pegawai'];
    $id_departemen = $_POST['id_departemen'];

    if ($id_pegawai && $id_departemen) {
        if (isset($_GET['op']) && $_GET['op'] == 'edit') {
            // Update data
            $query = "UPDATE pegawai_departemen SET id_departemen = '$id_departemen' WHERE id_pegawai = '$id_pegawai'";
        } else {
            // Insert data
            $query = "INSERT INTO pegawai_departemen (id_pegawai, id_departemen) VALUES ('$id_pegawai', '$id_departemen')";
        }

        $hasil = mysqli_query($koneksi, $query);
        if ($hasil) {
            $sukses = "Data berhasil disimpan";
        } else {
            $error = "Gagal menyimpan data";
        }
    } else {
        $error = "Silakan lengkapi semua data!";
    }
}
?>

<div class="mx-auto tampil">
    <div class="card">
        <div class="card-header text-white bg-secondary">Tambah/Edit Data Informasi Pegawai</div>
        <div class="card-body">
            <?php
            if ($error) {
                $_SESSION['error_message'] = $error;
                echo '<script>window.location.href = "index.php?page=informasi_pegawai";</script>';
                exit();
            } else if ($sukses) {
                $_SESSION['success_message'] = $sukses;
                echo '<script>window.location.href = "index.php?page=informasi_pegawai";</script>';
                exit();
            }
            ?>
            <form action="" method="POST">
                <!-- Pilih Pegawai -->
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Pegawai</label>
                    <div class="col-sm-10">
                        <select name="id_pegawai" class="form-control">
                            <option selected>--Pilih Pegawai--</option>
                            <?php
                            $queryPegawai = "SELECT id_pegawai, nama FROM pegawai ORDER BY nama ASC";
                            $hasilPegawai = mysqli_query($koneksi, $queryPegawai);
                            while ($pegawai = mysqli_fetch_array($hasilPegawai)) {
                                $selected = ($pegawai['id_pegawai'] == $id_pegawai) ? "selected" : "";
                                echo "<option value='{$pegawai['id_pegawai']}' $selected>{$pegawai['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Pilih Departemen -->
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Departemen</label>
                    <div class="col-sm-10">
                        <select name="id_departemen" class="form-control">
                            <option selected>--Pilih Departemen--</option>
                            <?php
                            $queryDept = "SELECT id_departemen, nama_departemen FROM departemen ORDER BY id_departemen ASC";
                            $hasilDept = mysqli_query($koneksi, $queryDept);
                            while ($departemen = mysqli_fetch_array($hasilDept)) {
                                $selected = ($departemen['id_departemen'] == $id_departemen) ? "selected" : "";
                                echo "<option value='{$departemen['id_departemen']}' $selected>{$departemen['nama_departemen']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="col-12">
                    <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    <button type="reset" class="btn btn-secondary"
                        onclick="window.location.href='index.php?page=informasi_pegawai'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Read Data -->
<div class="mx-auto tampil">
    <div class="card">
        <div class="card-header text-white bg-secondary">
            Data Informasi Pegawai
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
                        <th scope="col" style="text-align: center;">Aksi</th>
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
                            <td scope="row" style="text-align: center;">
                                <a href="index.php?page=informasi_pegawai&op=edit&id=<?php echo $id ?>">
                                    <button type="button" class="btn btn-warning">Edit</button>
                                </a>
                                <a href="index.php?page=informasi_pegawai&op=delete&id=<?php echo $id ?>">
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