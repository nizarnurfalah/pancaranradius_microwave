<?php

namespace App\Controllers;

use App\Models\ModelLokasi;

class Home extends BaseController
{
    protected $modelLokasi;

    public function __construct()
    {
        $this->modelLokasi = new ModelLokasi();
    }

    public function index()
    {
        $data = [
            'judul'  => 'Dashboard Overview',
            'page'   => 'v_dashboard',
            'lokasi' => $this->modelLokasi->getAllData(),
        ];
        return view('v_template', $data);
    }

    public function kalkulator()
    {
        $data = [
            'judul'  => 'Kalkulator Propagasi & Fresnel Zone Microwave',
            'page'   => 'v_microwave_calculator',
            'lokasi' => $this->modelLokasi->getAllData(),
        ];
        return view('v_template', $data);
    }

    public function viewMap()
    {
        $data = [
            'judul'  => 'View Map GIS',
            'page'   => 'v_viewmap',
            'lokasi' => $this->modelLokasi->getAllData(),
        ];
        return view('v_template', $data);
    }

    public function baseMap()
    {
        $data = [
            'judul'  => 'Base Map Switcher',
            'page'   => 'v_basemap',
            'lokasi' => $this->modelLokasi->getAllData(),
        ];
        return view('v_template', $data);
    }

    public function marker()
    {
        $data = [
            'judul'  => 'Marker & Radius Tower Antena',
            'page'   => 'v_marker',
            'lokasi' => $this->modelLokasi->getAllData(),
        ];
        return view('v_template', $data);
    }

    public function circle()
    {
        $data = [
            'judul'  => 'Multi-Radius Beam Antena',
            'page'   => 'v_circle',
            'lokasi' => $this->modelLokasi->getAllData(),
        ];
        return view('v_template', $data);
    }

    public function getkordinat()
    {
        $data = [
            'judul'  => 'Inspektor Koordinat Peta',
            'page'   => 'v_kordinat',
            'lokasi' => $this->modelLokasi->getAllData(),
        ];
        return view('v_template', $data);
    }

    public function getkordinat2()
    {
        $data = [
            'judul'  => 'Uji Koordinat Dalam Radius Coverage',
            'page'   => 'v_kordinat2',
            'lokasi' => $this->modelLokasi->getAllData(),
        ];
        return view('v_template', $data);
    }

    public function geolocation()
    {
        $data = [
            'judul'  => 'Geolokasi Pengguna & Tower Terdekat',
            'page'   => 'v_geolocation',
            'lokasi' => $this->modelLokasi->getAllData(),
        ];
        return view('v_template', $data);
    }
}
