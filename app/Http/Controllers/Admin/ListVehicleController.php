<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\RegisterVehicle;
use App\Models\Vehicle;
use App\Services\vehicleService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ListVehicleController extends Controller
{
    public function __construct(
        private vehicleService $vehicleServices
    ) {
    }
    public function index(): Response
    {
        $this->authorize('admin.viewAny');

        return Inertia::render('Admin/ManageVehicle/Index', [
            'vehicles' => RegisterVehicle::with(['vehicle'])->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('admin.create');

        return Inertia::render('Admin/ManageVehicle/Create');
    }

    public function store(StoreVehicleRequest $storeVehicleRequest): RedirectResponse
    {
        $this->vehicleServices->createVehicle(
            $storeVehicleRequest->validated()
        );

        return to_route('admin.vehicle.index');
    }

    public function show(Vehicle $vehicle): Response
    {
        $vehicle->load("user.any");

        return Inertia::render('Admin/ManageVehicle/Index', [
            'vehicle' => $vehicle,
        ]);
    }

    public function edit(Vehicle $vehicle): Response
    {
        $this->authorize('admin.update');

        return Inertia::render('Admin/ManageVehicle/Edit', [
            'vehicle' => $vehicle,
        ]);
    }

    public function update(Vehicle $vehicle, UpdateVehicleRequest $request): RedirectResponse
    {
        $this->authorize('admin.update');

        $this->vehicleServices->updateVehicle(
            $vehicle,
            $request->validated()
        );

        return to_route('admin.vehicle.index')
            ->withStatus('Updated');
    }

    public function destroy(string $id)
    {
        //
    }
}
