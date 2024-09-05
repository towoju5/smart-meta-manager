<?php

namespace Towoju5\SmartMetaManager\Trait;

use Towoju5\SmartMetaManager\Models\MetaDataModel;

trait MetaDataTrait
{
    public function metaData()
    {
        return $this->morphMany(MetaDataModel::class, 'metadatable');
    }

    public function setMeta($key, $value, $userId)
    {
        return $this->metaData()->updateOrCreate(
            ['key' => $key, 'user_id' => $userId],
            ['value' => $value]
        );
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

    public function getAllMetaForUser($userId)
    {
        return $this->metaData()->where('user_id', $userId)->get();
    }

    public function searchMeta($userId, $search)
    {
        return $this->metaData()
            ->where('user_id', $userId)
            ->where(function ($query) use ($search) {
                $query->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            })
            ->get();
    }

    public function hasMetaKeyValue($key, $value, $userId)
    {
        return $this->metaData()
            ->where('key', $key)
            ->where('value', $value)
            ->where('user_id', $userId)
            ->exists();
    }

}
