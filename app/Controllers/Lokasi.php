<?php

namespace App\Controllers;

use App\Models\ModelLokasi;

class Lokasi extends BaseController
{
    protected $ModelLokasi;

    public function __construct()
    {
        $this->ModelLokasi = new ModelLokasi();
    }

    public function index()
    {
        $data = [
            'judul'  => 'Manajemen Data Lokasi Tower Microwave',
            'page'   => 'lokasi/v_data_lokasi',
            'lokasi' => $this->ModelLokasi->getAllData(),
        ];
        return view('v_template', $data);
    }

    public function inputLokasi()
    {
        // Require admin role
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak! Menu Tambah Tower hanya dapat diakses oleh Administrator.');
            return redirect()->to(base_url('Lokasi'));
        }

        $data = [
            'judul' => 'Tambah Titik Tower BTS & Microwave Baru',
            'page'  => 'lokasi/v_input_lokasi',
        ];
        return view('v_template', $data);
    }

    public function insertData()
    {
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak!');
            return redirect()->to(base_url('Lokasi'));
        }

        if ($this->validate([
            'nama_lokasi'      => 'required|min_length[3]',
            'alamat_lokasi'    => 'required',
            'latitude'         => 'required',
            'longitude'        => 'required',
            'tinggi_tower'     => 'permit_empty|numeric',
            'frekuensi'        => 'permit_empty|numeric',
            'radius_jangkauan' => 'permit_empty|numeric',
            'foto_lokasi'      => 'permit_empty|max_size[foto_lokasi,5000]|mime_in[foto_lokasi,image/png,image/jpg,image/jpeg]'
        ])) {
            $foto = $this->request->getFile('foto_lokasi');
            $namaFile = null;

            if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                $namaFile = $foto->getRandomName();
                $foto->move('foto', $namaFile);
            }

            $data = [
                'kode_tower'       => $this->request->getPost('kode_tower') ?: 'TWR-' . rand(100, 999),
                'nama_lokasi'      => $this->request->getPost('nama_lokasi'),
                'alamat_lokasi'    => $this->request->getPost('alamat_lokasi'),
                'latitude'         => $this->request->getPost('latitude'),
                'longitude'        => $this->request->getPost('longitude'),
                'tinggi_tower'     => (float)($this->request->getPost('tinggi_tower') ?: 45),
                'frekuensi'        => (float)($this->request->getPost('frekuensi') ?: 5.8),
                'radius_jangkauan' => (float)($this->request->getPost('radius_jangkauan') ?: 2500),
                'tipe_antena'      => $this->request->getPost('tipe_antena') ?: 'Microwave Dish',
                'daya_pancar'      => (float)($this->request->getPost('daya_pancar') ?: 27),
                'status'           => $this->request->getPost('status') ?: 'Aktif',
                'foto_lokasi'      => $namaFile,
            ];

            $this->ModelLokasi->insertData($data);
            session()->setFlashdata('pesan', 'Titik tower microwave baru berhasil ditambahkan!');
            return redirect()->to(base_url('Lokasi'));
        } else {
            return redirect()->to(base_url('Lokasi/inputLokasi'))->withInput();
        }
    }

    public function editLokasi($id_lokasi)
    {
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak! Hanya Administrator yang dapat mengubah data tower.');
            return redirect()->to(base_url('Lokasi'));
        }

        $lokasi = $this->ModelLokasi->getDataById($id_lokasi);
        if (!$lokasi) {
            session()->setFlashdata('error', 'Data lokasi tower tidak ditemukan!');
            return redirect()->to(base_url('Lokasi'));
        }

        $data = [
            'judul'  => 'Edit Data Tower Microwave',
            'page'   => 'lokasi/v_edit_lokasi',
            'lokasi' => $lokasi,
        ];
        return view('v_template', $data);
    }

    public function updateData($id_lokasi)
    {
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak!');
            return redirect()->to(base_url('Lokasi'));
        }

        if ($this->validate([
            'nama_lokasi'      => 'required|min_length[3]',
            'alamat_lokasi'    => 'required',
            'latitude'         => 'required',
            'longitude'        => 'required',
            'tinggi_tower'     => 'permit_empty|numeric',
            'frekuensi'        => 'permit_empty|numeric',
            'radius_jangkauan' => 'permit_empty|numeric',
            'foto_lokasi'      => 'permit_empty|max_size[foto_lokasi,5000]|mime_in[foto_lokasi,image/png,image/jpg,image/jpeg]'
        ])) {
            $lokasi = $this->ModelLokasi->getDataById($id_lokasi);
            $foto = $this->request->getFile('foto_lokasi');

            if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                if ($lokasi && !empty($lokasi['foto_lokasi']) && file_exists('foto/' . $lokasi['foto_lokasi'])) {
                    @unlink('foto/' . $lokasi['foto_lokasi']);
                }
                $namaFile = $foto->getRandomName();
                $foto->move('foto', $namaFile);
            } else {
                $namaFile = $lokasi['foto_lokasi'] ?? null;
            }

            $data = [
                'kode_tower'       => $this->request->getPost('kode_tower') ?: ($lokasi['kode_tower'] ?? 'TWR-' . $id_lokasi),
                'nama_lokasi'      => $this->request->getPost('nama_lokasi'),
                'alamat_lokasi'    => $this->request->getPost('alamat_lokasi'),
                'latitude'         => $this->request->getPost('latitude'),
                'longitude'        => $this->request->getPost('longitude'),
                'tinggi_tower'     => (float)($this->request->getPost('tinggi_tower') ?: 45),
                'frekuensi'        => (float)($this->request->getPost('frekuensi') ?: 5.8),
                'radius_jangkauan' => (float)($this->request->getPost('radius_jangkauan') ?: 2500),
                'tipe_antena'      => $this->request->getPost('tipe_antena') ?: 'Microwave Dish',
                'daya_pancar'      => (float)($this->request->getPost('daya_pancar') ?: 27),
                'status'           => $this->request->getPost('status') ?: 'Aktif',
                'foto_lokasi'      => $namaFile,
            ];

            $this->ModelLokasi->updateData($id_lokasi, $data);
            session()->setFlashdata('pesan', 'Data tower microwave berhasil diperbarui!');
            return redirect()->to(base_url('Lokasi'));
        } else {
            return redirect()->to(base_url('Lokasi/editLokasi/' . $id_lokasi))->withInput();
        }
    }

    public function delete($id_lokasi)
    {
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak! Hanya Administrator yang dapat menghapus data.');
            return redirect()->to(base_url('Lokasi'));
        }

        $lokasi = $this->ModelLokasi->getDataById($id_lokasi);
        if ($lokasi && !empty($lokasi['foto_lokasi']) && file_exists('foto/' . $lokasi['foto_lokasi'])) {
            @unlink('foto/' . $lokasi['foto_lokasi']);
        }

        $this->ModelLokasi->deleteData($id_lokasi);
        session()->setFlashdata('pesan', 'Data lokasi tower berhasil dihapus!');
        return redirect()->to(base_url('Lokasi'));
    }

    public function pemetaanLokasi()
    {
        $data = [
            'judul'  => 'Pemetaan Seluruh Tower & Radius Jangkauan',
            'page'   => 'lokasi/v_pemetaan_lokasi',
            'lokasi' => $this->ModelLokasi->getAllData(),
        ];
        return view('v_template', $data);
    }
}
