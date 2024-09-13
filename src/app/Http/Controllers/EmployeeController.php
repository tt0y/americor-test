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
     */
    public function index()
    {
        try {
            // Кэшируем список сотрудников
            $employees = Cache::remember('employees', 60, function () {
                return Employee::paginate(10);
            });

            // Возвращаем коллекцию сотрудников через ресурс
            return EmployeeResource::collection($employees);
        } catch (\Exception $e) {
            // Если что-то пошло не так с кэшированием, выбрасываем кастомное исключение
            throw new CacheNotAvailableException();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        try {
            // Попытка кэширования данных сотрудника
            $employee = Cache::remember("employee_{$employee->id}", 60, function () use ($employee) {
                return $employee;
            });

            // Если кэширование прошло успешно, возвращаем сотрудника через ресурс
            return new EmployeeResource($employee);
        } catch (\Exception $e) {
            // Если что-то пошло не так с кэшированием, выбрасываем кастомное исключение
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
