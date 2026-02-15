<?php

namespace App\Http\Controllers;

use App\Models\PotentialCustomer;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function store(Request $req)
    {
        $data = $req->validate([
            'nama' => 'required|string|max:255',
            'no_wa' => 'required|string|max:30',
            'email' => 'required|email|max:150|unique:potential_customers,email',
            'nama_lembaga' => 'nullable|string|max:255',
        ]);

        PotentialCustomer::create($data);

        ActivityLogService::log(
            'create',
            PotentialCustomer::class,
            'Guest',
            null,
            null
        );

        return back()->with('success', 'Data berhasil dikirim.');
    }
}
