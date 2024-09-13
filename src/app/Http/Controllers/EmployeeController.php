<?php

namespace App\Http\Controllers;

use App\Exceptions\CacheNotAvailableException;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws CacheNotAvailableException
     */
    public function index()
    {
        try {
            // Caching the list of employees
            $employees = Cache::remember('employees', 60, function () {
                return Employee::paginate(10);
            });

            // Returning the collection of employees through a resource
            return EmployeeResource::collection($employees);
        } catch (\Exception $e) {
            // If something goes wrong with caching, throw a custom exception
            throw new CacheNotAvailableException();
        }
    }

    /**
     * Display the specified resource.
     * @throws CacheNotAvailableException
     */
    public function show(Employee $employee)
    {
        try {
            // Attempting to cache the employee data
            $employee = Cache::remember("employee_{$employee->id}", 60, function () use ($employee) {
                return $employee;
            });

            // If caching is successful, return the employee through a resource
            return new EmployeeResource($employee);
        } catch (\Exception $e) {
            // If something goes wrong with caching, throw a custom exception
            throw new CacheNotAvailableException();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:15',
        ]);

        $employee = Employee::create($validated);

        // Clear the cache after creating a new employee
        Cache::forget('employees');

        return new EmployeeResource($employee);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:15',
        ]);

        $employee->update($validated);

        // Очищаем кэш после обновления сотрудника
        Cache::forget('employees');
        Cache::forget("employee_{$employee->id}");

        return new EmployeeResource($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        // Clear the cache after deleting an employee
        Cache::forget('employees');
        Cache::forget("employee_{$employee->id}");

        return response()->noContent();
    }
}
