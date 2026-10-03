<?php namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'judul' => 'Pemetaan Lokasi',
            'page'  => 'lokasi/v_pemetaan_lokasi',
        ];
        return view('v_template', $data);
    }
}
