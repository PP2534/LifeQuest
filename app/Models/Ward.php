<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    /** @use HasFactory<\Database\Factories\WardFactory> */
    use HasFactory;
    protected $table = 'wards';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'province_id',
        'slug',
        'type',
        'name_with_type',
        'path',
        'path_with_type',
    ];
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

}
