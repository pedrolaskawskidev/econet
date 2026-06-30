<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    private function companyRules(string $status, ?Company $company = null): array
    {
        $cnpjRules = ['required', 'string', 'size:14'];

        if ($status === 'active') {
            $uniqueRule = Rule::unique('companies', 'cnpj')
                ->where(fn ($query) => $query
                    ->where('status', true)
                    ->whereNull('deleted_at'));

            if ($company) {
                $uniqueRule = $uniqueRule->ignore($company->id);
            }

            $cnpjRules[] = $uniqueRule;
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => $cnpjRules,
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    private function companyMessages(): array
    {
        return [
            'cnpj.unique' => 'Já existe uma empresa ativa com este CNPJ.',
        ];
    }

    private function sanitizeCompanyInput(Request $request): void
    {
        $request->merge([
            'cnpj' => preg_replace('/\D/', '', (string) $request->input('cnpj')),
        ]);
    }

    private function normalizeCompanyData(array $data): array
    {
        $data['status'] = $data['status'] === 'active';

        return $data;
    }

    public function index()
    {
        return Company::query()->paginate(10);
    }

    public function store(Request $request)
    {
        $this->sanitizeCompanyInput($request);

        $status = (string) $request->input('status');

        $data = $request->validate(
            $this->companyRules($status),
            $this->companyMessages(),
        );

        $data = $this->normalizeCompanyData($data);

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
        $company->load('employees');

        return response()->json([
            'data' => $company,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $this->sanitizeCompanyInput($request);

        $status = (string) $request->input('status');

        $data = $request->validate(
            $this->companyRules($status, $company),
            $this->companyMessages(),
        );

        $data = $this->normalizeCompanyData($data);

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
