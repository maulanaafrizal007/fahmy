<?php


require_once 'koneksi.php';


date_default_timezone_set('Asia/Jakarta');

$pesan = '';
$tipe  = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim(mysqli_real_escape_string($conn, $_POST['nama']     ?? ''));
    $instansi = trim(mysqli_real_escape_string($conn, $_POST['instansi'] ?? ''));
    $tujuan   = trim(mysqli_real_escape_string($conn, $_POST['tujuan']   ?? ''));
    $tanggal  = date('Y-m-d');   // WIB
    $waktu    = date('H:i:s');   // WIB

    if (empty($nama) || empty($instansi) || empty($tujuan)) {
        $pesan = 'Semua field wajib diisi!';
        $tipe  = 'danger';
    } else {
        $sql = "INSERT INTO buku_tamu (nama, instansi, tujuan, tanggal, waktu)
                VALUES ('$nama', '$instansi', '$tujuan', '$tanggal', '$waktu')";
        if (mysqli_query($conn, $sql)) {
            $pesan = 'Data berhasil disimpan. Selamat datang, <strong>' . htmlspecialchars($_POST['nama']) . '</strong>!';
            $tipe  = 'success';
        } else {
            $pesan = 'Gagal menyimpan data: ' . mysqli_error($conn);
            $tipe  = 'danger';
        }
    }
}


if (empty($pesan) && !empty($_GET['pesan'])) {
    $pesan = htmlspecialchars(urldecode($_GET['pesan']));
    $tipe  = ($_GET['status'] ?? '') === 'success' ? 'success' : 'danger';
}

// ── Ambil data untuk tabel ──
$cari   = trim(mysqli_real_escape_string($conn, $_GET['cari'] ?? ''));
$where  = $cari ? "WHERE nama LIKE '%$cari%' OR instansi LIKE '%$cari%' OR tujuan LIKE '%$cari%'" : '';
$result = mysqli_query($conn, "SELECT * FROM buku_tamu $where ORDER BY id DESC");
$total  = mysqli_num_rows($result);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Buku Tamu – SD Negeri 01 Kelapa Dua</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    :root {
      --blue-dark:  #0d2f6e;
      --blue-mid:   #1a4fa8;
      --blue-light: #3b82f6;
      --yellow:     #f5c518;
      --bg:         #eef3fb;
      --card:       #ffffff;
      --border:     #ccdaf5;
      --text:       #1e293b;
      --muted:      #64748b;
      --radius:     14px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Nunito', sans-serif;
      background: var(--bg);
      background-image:
        radial-gradient(ellipse at 0% 0%,   rgba(59,130,246,.12) 0%, transparent 50%),
        radial-gradient(ellipse at 100% 100%, rgba(13,47,110,.08) 0%, transparent 50%);
      min-height: 100vh;
      padding-bottom: 3rem;
    }

    /* ══ BANNER ══ */
    .banner-wrapper {
	width: auto;
	overflow: hidden;
	box-shadow: 0 4px 24px rgba(13,47,110,.18);
	display: flex;
    justify-content: center;
}
    .banner-wrapper img {
	width: 50%;
	height: auto;
	display: block;
	object-fit: cover;
}



   
    .main-wrapper { max-width: 960px; margin: 0 auto; padding: 2rem 1rem 0; }

   
    .section-title { text-align: center; margin-bottom: 1.5rem; }
    .section-title .pill {
      display: inline-flex; align-items: center; gap: .5rem;
      background: var(--blue-dark); color: #fff;
      font-size: .78rem; font-weight: 700; letter-spacing: .1em;
      text-transform: uppercase; padding: .35rem 1.1rem;
      border-radius: 50px; margin-bottom: .75rem;
    }
    .section-title h2 {
      font-family: 'Poppins', sans-serif; font-size: 1.5rem;
      font-weight: 700; color: var(--blue-dark);
    }
    .section-title p { color: var(--muted); font-size: .9rem; margin-top: .3rem; }

   
    .form-card {
      background: var(--card); border-radius: var(--radius);
      border: 1.5px solid var(--border);
      box-shadow: 0 8px 32px rgba(13,47,110,.10);
      overflow: hidden; max-width: 620px; margin: 0 auto;
      animation: fadeUp .5s ease both;
    }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

   
    .dt-strip {
      background: linear-gradient(90deg, var(--blue-dark), var(--blue-mid));
      padding: .75rem 1.5rem;
      display: flex; align-items: center; justify-content: center;
      flex-wrap: wrap; gap: .55rem;
    }

    .dt-chip {
      display: inline-flex; align-items: center; gap: .4rem;
      background: rgba(255,255,255,.13); border: 1px solid rgba(255,255,255,.22);
      color: #fff; font-size: .82rem; font-weight: 600;
      padding: .32rem .9rem; border-radius: 50px;
    }
    .dt-chip i { color: var(--yellow); }

    /* chip khusus live (tanggal + waktu) */
    .dt-chip.live {
      background: rgba(245,197,24,.15);
      border-color: rgba(245,197,24,.4);
      font-family: 'Poppins', sans-serif;
      font-size: .84rem;
    }
    .live-dot {
      display: inline-block;
      width: 7px; height: 7px;
      background: #22c55e;
      border-radius: 50%;
      flex-shrink: 0;
      animation: livepulse 1.4s ease infinite;
    }
    @keyframes livepulse {
      0%,100% { opacity: 1; transform: scale(1); }
      50%      { opacity: .35; transform: scale(.6); }
    }

  
    .form-body { padding: 1.8rem 2rem 2rem; }
    .form-label {
      font-size: .8rem; font-weight: 700; letter-spacing: .06em;
      text-transform: uppercase; color: var(--blue-dark); margin-bottom: .4rem;
    }
    .input-group-icon { position: relative; }
    .input-group-icon .icon {
      position: absolute; left: .95rem; top: 50%;
      transform: translateY(-50%); color: var(--blue-mid);
      font-size: 1rem; pointer-events: none; z-index: 2;
    }
    .input-group-icon .icon-top {
      position: absolute; left: .95rem; top: .85rem;
      color: var(--blue-mid); font-size: 1rem; pointer-events: none; z-index: 2;
    }
    .form-control {
      border: 1.5px solid var(--border); border-radius: 10px;
      font-family: 'Nunito', sans-serif; font-size: .93rem; color: var(--text);
      padding: .65rem 1rem .65rem 2.65rem; background: #f8fbff;
      transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .form-control:focus {
      border-color: var(--blue-mid); background: #fff;
      box-shadow: 0 0 0 3px rgba(26,79,168,.14); outline: none;
    }
    textarea.form-control { resize: none; padding-top: .7rem; }

   
    .timestamp-box {
      display: flex; align-items: center; flex-wrap: wrap; gap: .6rem;
      background: linear-gradient(135deg, #f0f7ff, #e8f1fd);
      border: 1.5px solid var(--border); border-radius: 10px;
      padding: .85rem 1.1rem; margin-bottom: 1.25rem;
      position: relative; overflow: hidden;
    }
    .timestamp-box::before {
      content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
      background: linear-gradient(var(--blue-dark), var(--blue-mid));
      border-radius: 4px 0 0 4px;
    }
    .ts-label {
      font-size: .72rem; font-weight: 800; text-transform: uppercase;
      letter-spacing: .1em; color: var(--blue-dark);
      display: flex; align-items: center; gap: .35rem; padding-left: .5rem;
    }
    .ts-label i { color: var(--yellow); font-size: .9rem; }
    .ts-sep { color: var(--border); font-weight: 300; }
    .ts-val {
      display: inline-flex; align-items: center; gap: .4rem;
      background: #fff; border: 1.5px solid var(--border);
      border-radius: 8px; padding: .32rem .85rem;
      font-family: 'Poppins', sans-serif; font-size: .88rem;
      font-weight: 700; color: var(--blue-dark);
    }
    .ts-val i { color: var(--blue-light); font-size: .9rem; }
    .ts-val.time-val { border-color: rgba(26,79,168,.3); }
    .ts-wib {
      font-size: .68rem; font-weight: 800; color: var(--blue-mid);
      background: #dbeafe; border-radius: 4px; padding: .05rem .35rem;
      letter-spacing: .06em;
    }
    .ts-auto-note {
      margin-left: auto; font-size: .7rem; color: var(--muted);
      display: flex; align-items: center; gap: .3rem;
    }
    .ts-auto-note .dot {
      width: 6px; height: 6px; background: #22c55e;
      border-radius: 50%; animation: livepulse 1.4s ease infinite;
    }

  
    .btn-submit {
      display: flex; align-items: center; justify-content: center; gap: .55rem;
      width: 100%;
      background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
      color: #fff; border: none; border-radius: 10px;
      font-family: 'Poppins', sans-serif; font-size: .95rem; font-weight: 600;
      padding: .85rem 1rem; cursor: pointer;
      transition: opacity .2s, transform .15s, box-shadow .2s;
    }
    .btn-submit .badge-dot { width: 8px; height: 8px; background: var(--yellow); border-radius: 50%; }
    .btn-submit:hover { opacity: .92; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(13,47,110,.28); }
    .btn-submit:active { transform: none; }

  
    .alert {
      border-radius: 10px; border: none; font-size: .88rem;
      padding: .8rem 1.1rem; margin-bottom: 1.25rem;
      display: flex; align-items: flex-start; gap: .6rem;
    }
    .alert-success { background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981; }
    .alert-danger  { background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444; }

    .field-sep { border: none; border-top: 1.5px dashed #d1e2f8; margin: 1.2rem 0; }


    .section-divider {
      display: flex; align-items: center; gap: 1rem;
      margin: 2.5rem 0 1.8rem;
    }
    .section-divider::before,
    .section-divider::after {
      content: ''; flex: 1; height: 2px;
      background: linear-gradient(90deg, transparent, var(--border), transparent);
    }
    .divider-label {
      display: inline-flex; align-items: center; gap: .5rem;
      background: var(--blue-dark); color: #fff;
      font-family: 'Poppins', sans-serif; font-size: .78rem;
      font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
      padding: .4rem 1.2rem; border-radius: 50px; white-space: nowrap;
    }


    .list-topbar {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: .75rem; margin-bottom: 1rem;
    }
    .list-summary {
      display: flex; align-items: center; gap: .5rem;
      font-family: 'Poppins', sans-serif; font-weight: 700;
      color: var(--blue-dark); font-size: .92rem;
    }
    .badge-total {
      background: var(--blue-dark); color: #fff;
      font-size: .7rem; font-weight: 700; padding: .18rem .65rem; border-radius: 50px;
    }

  
    .search-wrap { position: relative; }
    .search-wrap .search-icon {
      position: absolute; left: .85rem; top: 50%;
      transform: translateY(-50%); color: var(--blue-mid); pointer-events: none;
    }
    .search-input {
      padding: .5rem 1rem .5rem 2.4rem;
      border: 1.5px solid var(--border); border-radius: 8px;
      font-family: 'Nunito', sans-serif; font-size: .87rem;
      background: #fff; color: var(--text); width: 260px;
      transition: border-color .2s, box-shadow .2s;
    }
    .search-input:focus { outline: none; border-color: var(--blue-mid); box-shadow: 0 0 0 3px rgba(26,79,168,.12); }
    .btn-search {
      background: var(--blue-mid); color: #fff; border: none;
      border-radius: 8px; padding: .5rem .9rem; font-size: .82rem; cursor: pointer;
    }
    .btn-reset { font-size: .8rem; color: var(--blue-mid); text-decoration: none; white-space: nowrap; }
    .btn-reset:hover { text-decoration: underline; }


    .table-card {
      background: var(--card); border-radius: var(--radius);
      border: 1.5px solid var(--border);
      box-shadow: 0 4px 20px rgba(13,47,110,.08);
      overflow: hidden; animation: fadeUp .5s ease .1s both;
    }
    .table { margin: 0; font-size: .87rem; }
    .table thead tr { background: linear-gradient(90deg, var(--blue-dark), var(--blue-mid)); }
    .table thead th {
      color: #070606; font-weight: 700; font-size: .76rem; letter-spacing: .06em;
      text-transform: uppercase; border: none; padding: .85rem 1rem; white-space: nowrap;
    }
    .table tbody tr { border-bottom: 1px solid #e8f0fe; transition: background .15s; }
    .table tbody tr:last-child { border-bottom: none; }
    .table tbody tr:hover { background: #f0f6ff; }
    .table tbody td { padding: .75rem 1rem; vertical-align: middle; color: #334155; border: none; }

    .badge-no {
      background: var(--blue-dark); color: #fff;
      font-size: .7rem; font-weight: 700; padding: .16rem .55rem; border-radius: 50px;
    }
    .name-col  { font-weight: 700; color: var(--blue-dark); }
    .inst-col  { color: var(--muted); font-size: .83rem; }
    .tujuan-col { max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .date-chip {
      display: inline-flex; align-items: center; gap: .3rem;
      background: #f0f6ff; border: 1px solid var(--border);
      border-radius: 6px; padding: .2rem .6rem;
      font-size: .77rem; font-weight: 600; color: var(--blue-dark); white-space: nowrap;
    }
    .date-chip i { color: var(--blue-light); }

    .action-group { display: flex; gap: .4rem; }
    .btn-edit, .btn-del {
      display: inline-flex; align-items: center; gap: .3rem;
      border-radius: 7px; font-size: .77rem; font-weight: 700;
      padding: .3rem .7rem; text-decoration: none; border: none;
      cursor: pointer; transition: opacity .15s, transform .1s;
    }
    .btn-edit { background: #dbeafe; color: var(--blue-dark); }
    .btn-edit:hover { background: #bfdbfe; color: var(--blue-dark); }
    .btn-del  { background: #fee2e2; color: #b91c1c; }
    .btn-del:hover  { background: #fecaca; color: #b91c1c; }

    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--muted); }
    .empty-state i { font-size: 3rem; color: #d1e2f8; display: block; margin-bottom: 1rem; }
    .empty-state p { font-size: .9rem; }

 
    .bottom-bar {
      background: var(--blue-dark); color: rgba(255,255,255,.7);
      text-align: center; font-size: .78rem; padding: .75rem 1rem; margin-top: 2.5rem;
    }
    .bottom-bar strong { color: var(--yellow); }

   
    @media (max-width: 640px) {
      .tujuan-col { display: none; }
      .search-input { width: 160px; }
      .form-body { padding: 1.4rem 1.2rem 1.6rem; }
      .ts-auto-note { display: none; }
    }
    @media (max-width: 480px) {
      .list-topbar { flex-direction: column; align-items: flex-start; }
      .search-input { width: 100%; }
    }
  </style>
</head>
<body>

 
  <div class="banner-wrapper">
    <img src="banner.png" alt="Banner Buku Tamu SD Negeri 01 Kelapa Dua" width="100%" align="center" />
</div>

  <div class="main-wrapper">

    <!-- ══ SECTION TITLE ══ -->
    <div class="section-title mt-4">
      <div class="pill"><i class="bi bi-pencil-square"></i> Isi Data Kunjungan</div>
      <h2>Formulir Buku Tamu SD Negeri 01 Kelapa Dua</h2>
      <p>Mohon lengkapi data diri Anda sebelum memasuki lingkungan sekolah.</p>
    </div>

 
    <div class="form-card">

     
      <div class="form-body">

        <?php if (!empty($pesan)): ?>
        <div class="alert alert-<?= $tipe ?>" id="alert-msg">
          <i class="bi <?= $tipe === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?>"
             style="font-size:1.1rem;flex-shrink:0;margin-top:1px;"></i>
          <span><?= $pesan ?></span>
        </div>
        <?php endif; ?>

        <form method="POST" action="" id="form-tamu" novalidate>

          <!-- Nama -->
          <div class="mb-3">
            <label class="form-label" for="nama">
              <i class="bi bi-person-fill me-1"></i>Nama Lengkap
            </label>
            <div class="input-group-icon">
              <i class="bi bi-person icon"></i>
              <input type="text" class="form-control" id="nama" name="nama"
                placeholder="Masukkan nama lengkap Anda"
                value="<?= isset($_POST['nama']) && $tipe==='danger' ? htmlspecialchars($_POST['nama']) : '' ?>"
                required />
            </div>
          </div>

          <!-- Instansi -->
          <div class="mb-3">
            <label class="form-label" for="instansi">
              <i class="bi bi-building-fill me-1"></i>Instansi / Asal
            </label>
            <div class="input-group-icon">
              <i class="bi bi-building icon"></i>
              <input type="text" class="form-control" id="instansi" name="instansi"
                placeholder="Sekolah, lembaga, atau instansi Anda"
                value="<?= isset($_POST['instansi']) && $tipe==='danger' ? htmlspecialchars($_POST['instansi']) : '' ?>"
                required />
            </div>
          </div>

          <hr class="field-sep" />

          <!-- Tujuan -->
          <div class="mb-3">
            <label class="form-label" for="tujuan">
              <i class="bi bi-chat-left-dots-fill me-1"></i>Tujuan Kedatangan
            </label>
            <div class="input-group-icon">
              <i class="bi bi-chat-left-text icon-top"></i>
              <textarea class="form-control" id="tujuan" name="tujuan" rows="3"
                placeholder="Jelaskan keperluan / tujuan kunjungan Anda..."
                required><?= isset($_POST['tujuan']) && $tipe==='danger' ? htmlspecialchars($_POST['tujuan']) : '' ?></textarea>
            </div>
          </div>


          <div class="timestamp-box mb-4">
            <span class="ts-label">
              <i class="bi bi-clock-history"></i> Waktu Tercatat
            </span>
            <span class="ts-sep">|</span>
            <span class="ts-val">
              <i class="bi bi-calendar3"></i>
              <span id="ts-tanggal"><?php echo date('d/m/Y'); ?></span>
            </span>
            <span class="ts-val time-val">
              <i class="bi bi-stopwatch"></i>
              <span id="ts-jam"><?php echo date('H:i:s'); ?></span>
              <span class="ts-wib">WIB</span>
            </span>
            <span class="ts-auto-note">
              <span class="dot"></span> Live &bull;</span></div>

          <button type="submit" class="btn-submit">
            <span class="badge-dot"></span>
            <i class="bi bi-send-fill"></i>
            Simpan Data Tamu
          </button>

        </form>

        <p style="text-align:center;font-size:.77rem;color:var(--muted);margin-top:1rem;">&nbsp;</p>

      </div>
    </div><!-- /form-card -->


    <div class="section-divider">
      <span class="divider-label">
        <i class="bi bi-journal-text"></i> Daftar Kunjungan Tamu
      </span>
    </div>


    <div class="list-topbar">
      <div class="list-summary">
        <i class="bi bi-people-fill" style="color:var(--blue-mid);font-size:1.1rem;"></i>
        Data Tamu Tercatat
        <span class="badge-total"><?= $total ?> data</span>
      </div>
      <form method="GET" action="index.php" style="display:flex;align-items:center;gap:.45rem;flex-wrap:wrap;">
        <div class="search-wrap">
          <i class="bi bi-search search-icon"></i>
          <input type="text" class="search-input" name="cari"
            placeholder="Cari nama, instansi, tujuan..."
            value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>" />
        </div>
        <button type="submit" class="btn-search"><i class="bi bi-search"></i></button>
        <?php if ($cari): ?>
          <a href="index.php" class="btn-reset"><i class="bi bi-x-circle"></i> Reset</a>
        <?php endif; ?>
      </form>
    </div>


    <div class="table-card" id="tabel-tamu">
      <?php if ($total > 0): ?>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Instansi</th>
              <th class="tujuan-col">Tujuan</th>
              <th>Tanggal</th>
              <th>Waktu (WIB)</th>
            
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><span class="badge-no"><?= $no++ ?></span></td>
              <td class="name-col"><?= htmlspecialchars($row['nama']) ?></td>
              <td class="inst-col"><?= htmlspecialchars($row['instansi']) ?></td>
              <td class="tujuan-col" title="<?= htmlspecialchars($row['tujuan']) ?>">
                <?= htmlspecialchars($row['tujuan']) ?>
              </td>
              <td>
                <span class="date-chip">
                  <i class="bi bi-calendar3"></i>
                  <?= date('d/m/Y', strtotime($row['tanggal'])) ?>
                </span>
              </td>
              <td>
                <span class="date-chip">
                  <i class="bi bi-clock"></i>
                  <?= substr($row['waktu'], 0, 5) ?> WIB
                </span>
              </td>
              <td>
                <div class="action-group"></div>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
      <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>
          <?php if ($cari): ?>
            Tidak ada data yang cocok dengan pencarian
            &ldquo;<strong><?= htmlspecialchars($cari) ?></strong>&rdquo;.<br>
            <a href="index.php" style="font-size:.83rem;color:var(--blue-mid);">Tampilkan semua data</a>
          <?php else: ?>
            Belum ada data tamu yang tercatat. Jadilah yang pertama!
          <?php endif; ?>
        </p>
      </div>
      <?php endif; ?>
    </div>

</div><!-- /main-wrapper -->

<!-- ══ BOTTOM BAR ══ --><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    ═══════════════════════════

    const TZ = 'Asia/Jakarta';

    const HARI_ID  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const BULAN_ID = ['Januari','Februari','Maret','April','Mei','Juni',
                      'Juli','Agustus','September','Oktober','November','Desember'];

    function pad(n) { return String(n).padStart(2, '0'); }

    
    function jakartaNow() {
    
      const fmt = new Intl.DateTimeFormat('en-US', {
        timeZone: TZ,
        year: 'numeric', month: '2-digit', day: '2-digit',
        hour: '2-digit', minute: '2-digit', second: '2-digit',
        hour12: false
      });
      const parts = {};
      fmt.formatToParts(new Date()).forEach(p => { parts[p.type] = p.value; });
      return {
        year  : parseInt(parts.year),
        month : parseInt(parts.month) - 1,  // 0-based
        day   : parseInt(parts.day),
        hour  : parseInt(parts.hour) === 24 ? 0 : parseInt(parts.hour),
        minute: parseInt(parts.minute),
        second: parseInt(parts.second),
        
        weekday: new Date(new Date().toLocaleString('en-US', {timeZone: TZ})).getDay()
      };
    }

    function tick() {
      const t = jakartaNow();
      const H = pad(t.hour), M = pad(t.minute), S = pad(t.second);

  
      const tglLong = `${t.day} ${BULAN_ID[t.month]} ${t.year}`;
      document.getElementById('live-tanggal').textContent = tglLong;

   
      document.getElementById('live-jam').textContent = `${H}:${M}:${S}`;

    
      document.getElementById('ts-tanggal').textContent =
        `${pad(t.day)}/${pad(t.month + 1)}/${t.year}`;

  
      document.getElementById('ts-jam').textContent = `${H}:${M}:${S}`;
    }

    setInterval(tick, 1000);
    tick(); 
    
    document.getElementById('form-tamu').addEventListener('submit', function (e) {
      let ok = true;
      this.querySelectorAll('[required]').forEach(f => {
        if (!f.value.trim()) {
          f.style.borderColor = '#ef4444';
          f.style.boxShadow   = '0 0 0 3px rgba(239,68,68,.15)';
          ok = false;
        } else {
          f.style.borderColor = '';
          f.style.boxShadow   = '';
        }
      });
      if (!ok) e.preventDefault();
    });

    
    const alertMsg = document.getElementById('alert-msg');
    if (alertMsg) {
      setTimeout(() => {
        alertMsg.style.transition = 'opacity .5s';
        alertMsg.style.opacity    = '0';
        setTimeout(() => alertMsg.remove(), 500);
      }, 5000);
    }

    
    <?php if ($tipe === 'success'): ?>
    document.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
        document.getElementById('tabel-tamu')
          ?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 600);
    });
    <?php endif; ?>

   
    document.querySelector('.search-input')
      ?.addEventListener('keyup', e => {
        if (e.key === 'Enter') e.target.closest('form').submit();
      });
  </script>
</body>
</html>
