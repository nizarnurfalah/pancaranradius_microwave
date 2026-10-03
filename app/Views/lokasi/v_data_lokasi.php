<div class="gis-card">
    <div class="gis-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-table text-primary"></i>
            <span>Daftar Data Tower & Spesifikasi Antena</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if(session()->get('role') === 'admin'): ?>
                <a href="<?= base_url('Lokasi/inputLokasi') ?>" class="btn btn-sm btn-primary rounded-2 px-3">
                    <i class="fa-solid fa-plus-circle me-1"></i> Tambah Tower Baru
                </a>
            <?php else: ?>
                <span class="badge bg-light border text-secondary py-2 px-3"><i class="fa-solid fa-lock me-1"></i> Mode View-Only (Admin untuk mengedit)</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table id="datatablesSimple" class="table table-hover align-middle mb-0 table-formal border">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Foto</th>
                        <th>Kode & Nama Tower</th>
                        <th>Alamat</th>
                        <th>Koordinat</th>
                        <th>Spesifikasi</th>
                        <th>Radius</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($lokasi)): ?>
                        <?php foreach($lokasi as $idx => $t): ?>
                            <tr>
                                <td class="text-secondary"><?= $idx + 1 ?></td>
                                <td>
                                    <?php if(!empty($t['foto_lokasi']) && file_exists('foto/' . $t['foto_lokasi'])): ?>
                                        <img src="<?= base_url('foto/' . $t['foto_lokasi']) ?>" alt="Tower" class="rounded border" style="width: 45px; height: 45px; object-fit: cover; cursor: pointer;" onclick="previewImage('<?= base_url('foto/' . $t['foto_lokasi']) ?>', '<?= esc($t['nama_lokasi']) ?>')">
                                    <?php else: ?>
                                        <div class="rounded bg-light border d-flex align-items-center justify-content-center text-secondary" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                            <i class="fa-solid fa-tower-broadcast"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($t['nama_lokasi']) ?></div>
                                    <div class="small text-muted font-monospace"><?= esc($t['kode_tower'] ?? 'TWR-' . $t['id_lokasi']) ?></div>
                                </td>
                                <td class="small text-secondary" style="max-width: 220px;"><?= esc($t['alamat_lokasi']) ?></td>
                                <td class="small font-monospace text-dark">
                                    <div><?= round((float)$t['latitude'], 5) ?></div>
                                    <div><?= round((float)$t['longitude'], 5) ?></div>
                                </td>
                                <td class="small text-dark">
                                    <div><strong><?= esc($t['frekuensi'] ?? 5.8) ?> GHz</strong> &bull; <?= esc($t['tinggi_tower'] ?? 45) ?>m</div>
                                    <div class="text-muted" style="font-size: 0.78rem;"><?= esc($t['tipe_antena'] ?? 'Microwave Dish') ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-light border text-primary px-2 py-1">
                                        <?= number_format((float)($t['radius_jangkauan'] ?? 2500), 0, ',', '.') ?> m
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= ($t['status'] ?? 'Aktif') === 'Aktif' ? 'bg-success' : (($t['status'] ?? '') === 'Maintenance' ? 'bg-warning text-dark' : 'bg-danger') ?> rounded-pill">
                                        <?= esc($t['status'] ?? 'Aktif') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('Lokasi/pemetaanLokasi') ?>" class="btn btn-outline-secondary" title="Lihat di Peta">
                                            <i class="fa-solid fa-map"></i>
                                        </a>
                                        <?php if(session()->get('role') === 'admin'): ?>
                                            <a href="<?= base_url('Lokasi/editLokasi/' . $t['id_lokasi']) ?>" class="btn btn-outline-primary" title="Edit Tower">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" title="Hapus Tower" onclick="confirmDelete(<?= $t['id_lokasi'] ?>, '<?= esc($t['nama_lokasi']) ?>')">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function previewImage(url, title) {
        Swal.fire({
            title: title,
            imageUrl: url,
            imageAlt: title,
            confirmButtonColor: '#1d4ed8'
        });
    }

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Data Tower?',
            text: `Apakah Anda yakin ingin menghapus "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('Lokasi/delete/') ?>/' + id;
            }
        });
    }
</script>
