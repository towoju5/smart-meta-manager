<?php

namespace Towoju5\SmartMetaManager\Models;

use Illuminate\Database\Eloquent\Model;

class MetaDataModel extends Model
{
    protected $fillable = ['key', 'value', 'user_id', 'metadatable_type', 'metadatable_id'];

    public function metadatable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(config('meta_models.user'));
    }
}
