<?php

namespace App\Http\Controllers\Api;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TenantResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\TenantCollection;
use App\Http\Requests\TenantStoreRequest;
use App\Http\Requests\TenantUpdateRequest;

class TenantController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Tenant::class);

        $search = $request->get('search', '');

        $tenants = Tenant::search($search)
            ->latest()
            ->paginate();

        return new TenantCollection($tenants);
    }

    /**
     * @param \App\Http\Requests\TenantStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TenantStoreRequest $request)
    {
        $this->authorize('create', Tenant::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $validated['system_settings'] = json_decode(
            $validated['system_settings'],
            true
        );

        $validated['settings'] = json_decode($validated['settings'], true);

        $tenant = Tenant::create($validated);

        return new TenantResource($tenant);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Tenant $tenant
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Tenant $tenant)
    {
        $this->authorize('view', $tenant);

        return new TenantResource($tenant);
    }

    /**
     * @param \App\Http\Requests\TenantUpdateRequest $request
     * @param \App\Models\Tenant $tenant
     * @return \Illuminate\Http\Response
     */
    public function update(TenantUpdateRequest $request, Tenant $tenant)
    {
        $this->authorize('update', $tenant);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($tenant->image) {
                Storage::delete($tenant->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $validated['system_settings'] = json_decode(
            $validated['system_settings'],
            true
        );

        $validated['settings'] = json_decode($validated['settings'], true);

        $tenant->update($validated);

        return new TenantResource($tenant);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Tenant $tenant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Tenant $tenant)
    {
        $this->authorize('delete', $tenant);

        if ($tenant->image) {
            Storage::delete($tenant->image);
        }

        $tenant->delete();

        return response()->noContent();
    }
}
