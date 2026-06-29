<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{

    public function index()
    {
        return Company::query()->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'size:18', 'unique:companies,cnpj'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $company = Company::create($data);

        return response()->json([
            'message' => 'Empresa cadastrada com sucesso.',
            'data' => $company,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company->load('users');

        return response()->json([
            'data' => $company,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => [
                'required',
                'string',
                'size:18',
                Rule::unique('companies', 'cnpj')->ignore($company->id),
            ],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $company->update($data);

        return response()->json([
            'message' => 'Empresa atualizada com sucesso.',
            'data' => $company,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            'message' => 'Empresa e funcionários excluidos com sucesso.',
        ]);
    }
}
