<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyEmployee;
use Illuminate\Http\Request;

class CompanyEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CompanyEmployee::query()
            ->with('company')
            ->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
        ]);

        $employee = CompanyEmployee::create($data);

        return response()->json([
            'message' => 'Funcionário cadastrado com sucesso.',
            'data' => $employee->load('company'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyEmployee $employee)
    {
        return response()->json([
            'data' => $employee->load('company'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyEmployee $employee)
    {
        $data = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
        ]);

        $employee->update($data);

        return response()->json([
            'message' => 'Funcionário atualizado com sucesso.',
            'data' => $employee->load('company'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyEmployee $employee)
    {
        $employee->delete();

        return response()->json([
            'message' => 'Funcionário removido com sucesso.',
        ]);
    }

    public function storeByCompany(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
        ]);

        $employee = $company->employees()->create($data);

        return response()->json([
            'message' => 'Funcionário vinculado com sucesso.',
            'data' => $employee,
        ], 201);
    }

    public function destroyByCompany(Company $company, CompanyEmployee $companyEmployee)
    {
        abort_if(
            $companyEmployee->company_id !== $company->id,
            404,
            'Funcionário não pertence a esta empresa.'
        );

        $companyEmployee->delete();

        return response()->json([
            'message' => 'Funcionário removido da empresa com sucesso.',
        ]);
    }
}
