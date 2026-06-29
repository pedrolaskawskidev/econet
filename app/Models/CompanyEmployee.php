<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyEmployee extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'company_employee';

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'role'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    protected static function newFactory()
   {
      return EmployeeFactory::new();
   }
}
