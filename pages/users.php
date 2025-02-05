<?php
// Validasi user
if (!isset($_SESSION['role'])) {
    echo '<script>window.location.href = "login.php";</script>';
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
$username = $password = $error = $sukses = "";

// Edit
$op = isset($_GET['op']) ? $_GET['op'] : "";

if ($op == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM users WHERE id_user = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $hasil = mysqli_stmt_execute($stmt);

    if ($hasil) {
        $sukses = "Data berhasil dihapus";
    } else {
        $error = "Gagal melakukan delete data";
    }
}

// Create
if (isset($_POST['simpan'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username && $password) {
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $password_hashed);
        $hasil = mysqli_stmt_execute($stmt);

        if ($hasil) {
            $sukses = "Berhasil memasukkan data.";
        } else {
            $error = "Gagal memasukkan data.";
        }
    } else {
        $error = "Silahkan Masukkan semua data.";
    }
}

// Notifikasi Error/Sukses
if ($error || $sukses) {
    $_SESSION['success_message'] = $error ? $error : $sukses;
    echo '<script>window.location.href = "index.php?page=users";</script>';
    exit();
}
?>

<!-- Form Tambah/Edit Data -->
<div class="mx-auto tampil">
    <div class="card">
        <div class="card-header text-white bg-secondary">Tambah Data User</div>
        <div class="card-body">
            <form action="" method="POST">
                <div class="mb-3 row">
                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" name="username" maxlength="30" value="<?php echo htmlspecialchars($username); ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password" maxlength="15">
                    </div>
                </div>
                <div class="col-12">
                    <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    <button type="reset" class="btn btn-secondary" onclick="window.location.href='index.php?page=users'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabel Data User -->
<div class="mx-auto tampil">
    <div class="card">
        <div class="card-header text-white bg-secondary">Data User</div>
        <div class="card-body tabel-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query2 = "SELECT id_user, username FROM users ORDER BY id_user ASC";
                    $hasil2 = mysqli_query($koneksi, $query2);
                    $urut = 1;

                    while ($r2 = mysqli_fetch_array($hasil2)) {
                        $id = $r2['id_user'];
                        $username = $r2['username'];
                    ?>
                        <tr>
                            <th scope="row"><?php echo $urut++; ?></th>
                            <td><?php echo htmlspecialchars($username); ?></td>
                            <td scope="row" style="text-align: center;">
                                <?php if ($username !== "admin") { ?>
                                    <a href="index.php?page=users&op=delete&id=<?php echo $id ?>">
                                        <button type="button" class="btn btn-danger" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</button>
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
