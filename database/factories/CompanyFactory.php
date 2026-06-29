<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{

    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => 'Econet Editora e Consultoria Empresarial LTDA',
            'cnpj' => '05330384000124',
        ];
    }
}
