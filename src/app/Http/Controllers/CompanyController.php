<?php

namespace App\Http\Controllers;

use App\Exceptions\CacheNotAvailableException;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws CacheNotAvailableException
     */
    public function index(): AnonymousResourceCollection
    {
        try {
            // Cache the query result for 60 seconds
            $companies = Cache::remember('companies', 60, function () {
                return Company::paginate(10);
            });

            // Return the collection of companies through a resource
            return CompanyResource::collection($companies);
        } catch (\Exception $e) {
            // If something goes wrong with caching, throw a custom exception
            throw new CacheNotAvailableException();
        }
    }

    /**
     * Display the specified resource.
     * @throws CacheNotAvailableException
     */
    public function show(Company $company): CompanyResource
    {
        try {
            // Cache the data of a single company
            $company = Cache::remember("company_{$company->id}", 60, function () use ($company) {
                return $company;
            });

            // Return the company through a resource
            return new CompanyResource($company);
        } catch (\Exception $e) {
            // If something goes wrong with caching, throw a custom exception
            throw new CacheNotAvailableException();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request): CompanyResource
    {
        $company = Company::create($request->validated());

        // Clear the cache after creating a new company
        Cache::forget('companies');

        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company): CompanyResource
    {
        $company->update($request->validated());

        // Clear the cache after updating the company
        Cache::forget('companies');
        Cache::forget("company_{$company->id}");

        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company): Response
    {
        $company->delete();

        // Clear the cache after deleting the company
        Cache::forget('companies');
        Cache::forget("company_{$company->id}");

        return response()->noContent();
    }
}
