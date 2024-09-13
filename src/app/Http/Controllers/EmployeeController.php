<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use phpseclib3\Math\PrimeField\Integer;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Кэшируем список сотрудников
        $employees = Cache::remember('employees', 60, function () {
            return Employee::paginate(10);
        });

        // Возвращаем коллекцию сотрудников через ресурс
        return EmployeeResource::collection($employees);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        // Кэшируем данные одного сотрудника
        $employee = Cache::remember("employee_{$employee->id}", 60, function () use ($employee) {
            return $employee;
        });

        // Возвращаем сотрудника через ресурс
        return new EmployeeResource($employee);
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

        // Очищаем кэш после создания нового сотрудника
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

        // Очищаем кэш после удаления сотрудника
        Cache::forget('employees');
        Cache::forget("employee_{$employee->id}");

        return response()->noContent();
    }
}
