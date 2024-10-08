<?php

namespace App\Http\Controllers;

use App\Exceptions\CacheNotAvailableException;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws CacheNotAvailableException
     */
    public function index(): AnonymousResourceCollection
    {
        try {
            $employees = Cache::remember('employees', 60, function () {
                return Employee::paginate(10);
            });

            return EmployeeResource::collection($employees);
        } catch (\Exception $e) {
            throw new CacheNotAvailableException;
        }
    }

    /**
     * Display the specified resource.
     *
     * @throws CacheNotAvailableException
     */
    public function show(Employee $employee): EmployeeResource
    {
        try {
            $employee = Cache::remember("employee_{$employee->id}", 60, function () use ($employee) {
                return $employee;
            });

            return new EmployeeResource($employee);
        } catch (\Exception $e) {
            throw new CacheNotAvailableException;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request): EmployeeResource
    {
        $employee = Employee::create($request->validated());

        // Clear the cache after creating a new employee
        Cache::forget('employees');

        return new EmployeeResource($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): EmployeeResource
    {
        $employee->update($request->validated());

        // Clear the cache after updating an employee
        Cache::forget('employees');
        Cache::forget("employee_$employee->id");

        return new EmployeeResource($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee): Response
    {
        $employee->delete();

        // Clear the cache after deleting an employee
        Cache::forget('employees');
        Cache::forget("employee_$employee->id");

        return response()->noContent();
    }
}
