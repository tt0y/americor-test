<?php

namespace App\Http\Controllers;

use App\Exceptions\CacheNotAvailableException;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Кэшируем результат запроса на 60 секунд
            $companies = Cache::remember('companies', 60, function () {
                return Company::paginate(10);
            });

            // Возвращаем коллекцию компаний через ресурс
            return CompanyResource::collection($companies);
        } catch (\Exception $e) {
            // Если что-то пошло не так с кэшированием, выбрасываем кастомное исключение
            throw new CacheNotAvailableException();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        try {
            // Кэшируем данные одной компании
            $company = Cache::remember("company_{$company->id}", 60, function () use ($company) {
                return $company;
            });

            // Возвращаем компанию через ресурс
            return new CompanyResource($company);
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
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
        ]);

        $company = Company::create($validated);

        // Очищаем кэш после создания новой компании
        Cache::forget('companies');

        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
        ]);

        $company->update($validated);

        // Очищаем кэш после обновления компании
        Cache::forget('companies');
        Cache::forget("company_{$company->id}");

        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        // Очищаем кэш после удаления компании
        Cache::forget('companies');
        Cache::forget("company_{$company->id}");

        return response()->noContent();
    }
}
