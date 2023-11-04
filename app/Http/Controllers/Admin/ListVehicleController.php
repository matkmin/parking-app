<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ListVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $this->authorize('admin.viewAny');


        return Inertia::render('Admin/Index', [
            'vehicles' => Vehicle::with(['vehicleRegister'])->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('admin.create');

        return Inertia::render('Admin/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show(Vehicle $vehicle): Response
    // {
    //     $vehicle->load("user.any");

    //     return Inertia::render('Admin/Index', [
    //         'vehicle' => $vehicle,
    //     ]);
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(): Response
    {
        $this->authorize('admin.update');

        return Inertia::render('Admin/Edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
