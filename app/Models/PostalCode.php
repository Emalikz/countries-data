<?php
// app/Models/PostalCode.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'code', 'city_id'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
