<?php

namespace App\Http\Controllers;

use App\Http\Requests\PotentialCustomerStoreRequest;
use App\Http\Requests\PotentialCustomerUpdateRequest;
use App\Models\PotentialCustomer;
use Illuminate\Http\Request;
use App\Http\Resources\PotentialCustomerResource;
use App\Services\ActivityLogService;
use Inertia\Inertia;

class PotentialCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $query = PotentialCustomer::query();

        if ($req->search) {
            $query->where(function ($q) use ($req) {
                $q->where('nama', 'like', "%{$req->search}%")
                ->orWhere('email', 'like', "%{$req->search}%")
                ->orWhere('no_wa', 'like', "%{$req->search}%")
                ->orWhere('nama_lembaga', 'like', "%{$req->search}%");
            });
        }

        $leads = $query->latest()->paginate(10)->withQueryString();

        if ($req->wantsJson()) {
            return PotentialCustomerResource::collection($leads);
        }

        return Inertia::render('PotentialCustomer/Index', [
            'leads' => $leads,
            'filters' => $req->only('search')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PotentialCustomerStoreRequest $req)
    {
        $pc = PotentialCustomer::create($req->validated());

        ActivityLogService::log(
            'create',
            PotentialCustomer::class,
            $pc->id,
            null,
            $pc->toArray()
        );

        return back()->with('success', 'Calon Customer Berhasil di Tambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PotentialCustomerUpdateRequest $req, PotentialCustomer $lead)
    {
        $old = $lead->getOriginal();

        $lead->update($req->validated());

        ActivityLogService::log(
            'update',
            PotentialCustomer::class,
            $lead->id,
            $old,
            $lead->toArray()
        );

        if ($req->wantsJson()) {
            return new PotentialCustomerResource($lead);
        }

        return back()->with('success', 'Calon Customer berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PotentialCustomer $lead)
    {
        $old = $lead->toArray();
        $lead->delete();

        ActivityLogService::log(
            'delete',
            PotentialCustomer::class,
            $lead->id,
            $old,
            null
        );

        return back()->with('message', 'Calon Customer berhasil di Delete');
    }
}
