<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = ['model_id', 'attachment','title','model_type'];

    protected $appends = ['file'];
    public function getFileAttribute()
    {
        return array_key_exists('attachment', $this->attributes) ? ($this->attributes['attachment'] != null ? asset('storage/files/' . $this->attributes['attachment']) : null) : null;

    }

}
