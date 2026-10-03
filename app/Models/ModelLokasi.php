<?php

namespace App\Models;

class ModelLokasi
{
    protected string $storageFile;

    protected array $defaultTowers = [
        [
            'id_lokasi' => 1,
            'kode_tower' => 'TWR-CMI051',
            'nama_lokasi' => 'Tower BTS Microwave Cimahi Tengah',
            'alamat_lokasi' => 'Jl. Jend. H. Amir Machmud No. 51, Karangmekar, Cimahi Tengah',
            'latitude' => '-6.889439',
            'longitude' => '107.558329',
            'tinggi_tower' => 52.0,
            'frekuensi' => 5.8,
            'radius_jangkauan' => 3200,
            'tipe_antena' => 'Solid Dish Microwave (0.6m)',
            'daya_pancar' => 28,
            'status' => 'Aktif',
            'foto_lokasi' => '1750234309_b28a637d4c69933c753d.png'
        ],
        [
            'id_lokasi' => 2,
            'kode_tower' => 'TWR-CMI057',
            'nama_lokasi' => 'Tower Microwave Kolonel Masturi',
            'alamat_lokasi' => 'Jl. Kolonel Masturi No. 57, Cipageran, Cimahi Utara',
            'latitude' => '-6.865278',
            'longitude' => '107.541138',
            'tinggi_tower' => 60.0,
            'frekuensi' => 11.0,
            'radius_jangkauan' => 4500,
            'tipe_antena' => 'High Performance Microwave Dish',
            'daya_pancar' => 30,
            'status' => 'Aktif',
            'foto_lokasi' => '1750234309_b28a637d4c69933c753d.png'
        ],
        [
            'id_lokasi' => 3,
            'kode_tower' => 'TWR-CMI070',
            'nama_lokasi' => 'Tower Repeater Cihanjuang Hill',
            'alamat_lokasi' => 'Jl. Cihanjuang No. 70, Cibabat, Cimahi Utara',
            'latitude' => '-6.843809',
            'longitude' => '107.567880',
            'tinggi_tower' => 45.0,
            'frekuensi' => 5.8,
            'radius_jangkauan' => 2800,
            'tipe_antena' => 'Sectoral & Microwave Dish',
            'daya_pancar' => 25,
            'status' => 'Aktif',
            'foto_lokasi' => '1750234309_b28a637d4c69933c753d.png'
        ],
        [
            'id_lokasi' => 4,
            'kode_tower' => 'TWR-CMI119',
            'nama_lokasi' => 'Tower Hub Mahar Martanegara',
            'alamat_lokasi' => 'Jl. Mahar Martanegara No. 119, Utama, Cimahi Selatan',
            'latitude' => '-6.905289',
            'longitude' => '107.544570',
            'tinggi_tower' => 48.0,
            'frekuensi' => 18.0,
            'radius_jangkauan' => 2100,
            'tipe_antena' => 'Dual Polarized Microwave Dish',
            'daya_pancar' => 24,
            'status' => 'Maintenance',
            'foto_lokasi' => '1750234309_b28a637d4c69933c753d.png'
        ],
        [
            'id_lokasi' => 5,
            'kode_tower' => 'TWR-BDO012',
            'nama_lokasi' => 'Tower Backbone Pasteur Gateway',
            'alamat_lokasi' => 'Jl. Dr. Djunjunan No. 143, Sukagalih, Sukajadi, Kota Bandung',
            'latitude' => '-6.892150',
            'longitude' => '107.585400',
            'tinggi_tower' => 55.0,
            'frekuensi' => 7.5,
            'radius_jangkauan' => 5000,
            'tipe_antena' => 'Backbone Long Haul Microwave',
            'daya_pancar' => 32,
            'status' => 'Aktif',
            'foto_lokasi' => '1750234309_b28a637d4c69933c753d.png'
        ]
    ];

    public function __construct()
    {
        $this->storageFile = WRITEPATH . 'towers_data.json';
        if (!file_exists($this->storageFile)) {
            $this->saveData($this->defaultTowers);
        }
    }

    protected function loadData(): array
    {
        if (file_exists($this->storageFile)) {
            $content = file_get_contents($this->storageFile);
            $data = json_decode($content, true);
            if (is_array($data) && !empty($data)) {
                return $data;
            }
        }
        return $this->defaultTowers;
    }

    protected function saveData(array $data): void
    {
        @file_put_contents($this->storageFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function getAllData(): array
    {
        return $this->loadData();
    }

    public function getActiveData(): array
    {
        $all = $this->loadData();
        return array_values(array_filter($all, fn($t) => ($t['status'] ?? 'Aktif') === 'Aktif'));
    }

    public function getDataById($id_lokasi): ?array
    {
        $all = $this->loadData();
        foreach ($all as $t) {
            if ((int)$t['id_lokasi'] === (int)$id_lokasi) {
                return $t;
            }
        }
        return null;
    }

    public function insertData(array $data): int
    {
        $all = $this->loadData();
        $maxId = 0;
        foreach ($all as $t) {
            if ((int)$t['id_lokasi'] > $maxId) {
                $maxId = (int)$t['id_lokasi'];
            }
        }
        $data['id_lokasi'] = $maxId + 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        array_unshift($all, $data);
        $this->saveData($all);
        return $data['id_lokasi'];
    }

    public function updateData($id_lokasi, array $data): bool
    {
        $all = $this->loadData();
        $found = false;
        foreach ($all as $idx => $t) {
            if ((int)$t['id_lokasi'] === (int)$id_lokasi) {
                $data['id_lokasi'] = (int)$id_lokasi;
                $data['created_at'] = $t['created_at'] ?? date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $all[$idx] = array_merge($t, $data);
                $found = true;
                break;
            }
        }
        if ($found) {
            $this->saveData($all);
        }
        return $found;
    }

    public function deleteData($id_lokasi): bool
    {
        $all = $this->loadData();
        $new = [];
        foreach ($all as $t) {
            if ((int)$t['id_lokasi'] !== (int)$id_lokasi) {
                $new[] = $t;
            }
        }
        $this->saveData($new);
        return true;
    }
}
