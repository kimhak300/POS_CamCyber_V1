<?php

namespace App\Models\Product;

// ===================================================>> Core Library
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// ===================================================>> Custom Library
use App\Models\Product\Type;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';


    public function type(): BelongsTo //M:1
    {
        return $this->belongsTo(Type::class, 'type_id','id')->select('id', 'name');
    }
}
