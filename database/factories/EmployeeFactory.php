<?php

namespace Database\Factories;

use App\Models\CompanyEmployee;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends Factory<CompanyEmployee>
 */
class EmployeeFactory extends Factory
{
    protected $model = CompanyEmployee::class;

    public function definition(): array
    {
        return [
            'name' => 'Pedro Laskawski',
            'email' => 'pedro.laskawski@econet.com',
            'role' => 'Desenvolvedor Fullstack',
            'company_id' => 1
        ];
    }
}
