<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
   use SoftDeletes, HasFactory;

   protected $fillable = [
      'name',
      'cnpj',
      'status'
   ];

   public function employee(): HasMany
   {
      return $this->hasMany(CompanyEmployee::class);
   }

   protected static function newFactory()
   {
      return CompanyFactory::new();
   }
}
