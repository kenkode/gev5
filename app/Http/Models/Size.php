<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model {
  
    protected $fillable = [
      "id",
      "gas_id",
      "size",
      "price"
    ];
}

 ?>
