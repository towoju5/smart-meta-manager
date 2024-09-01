<?php

namespace Towoju5\SmartMetaManager\Models;

use App\Models\User;
// use App\Modules\user\Models\User;
use Illuminate\Database\Eloquent\Model;

class MetaDataModel extends Model
{
    protected $fillable = ['key', 'value', 'user_id'];

    public function metadatable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
