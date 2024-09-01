<?php

namespace Towoju5\SmartMetaManager\Trait\Models;

use Towoju5\SmartMetaManager\MetaDataModel;
use Towoju5\SmartMetaManager\Models\MetaData;

trait MetaDataTrait
{
    public function metaData()
    {
        return $this->morphMany(MetaDataModel::class, 'metadatable');
    }

    public function setMeta($key, $value, $userId)
    {
        $meta = $this->metaData()->updateOrCreate(
            ['key' => $key, 'user_id' => $userId],
            ['value' => $value]
        );

        return $meta;
    }

    public function getMeta($key, $userId, $default = null)
    {
        $meta = $this->metaData()->where('key', $key)->where('user_id', $userId)->first();
        return $meta ? $meta->value : $default;
    }

    public function deleteMeta($key, $userId)
    {
        return $this->metaData()->where('key', $key)->where('user_id', $userId)->delete();
    }
}
