<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    protected $table = 'setores';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome'
    ];

    public function usuarios(){
        return $this->hasMany(User::class, 'id_setor', 'id');
    }

}
