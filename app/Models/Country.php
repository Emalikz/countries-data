<?php
// app/Models/Country.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'iso2', 'iso3', 'phone_code'];

    public function states()
    {
        return $this->hasMany(State::class);
    }
}
