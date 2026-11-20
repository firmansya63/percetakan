<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email', 'logo'];

    /**
     * Get the single company profile record, creating it when it does not exist yet.
     */
    public static function current(): self
    {
        return static::query()->oldest('id')->first()
            ?? static::query()->create(['name' => config('app.name', 'Percetakan')]);
    }
}
