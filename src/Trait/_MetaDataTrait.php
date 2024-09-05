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
        if (!$this->exists) {
            $this->save();
        }

        return $this->metaData()->updateOrCreate(
            ['key' => $key, 'user_id' => $userId],
            [
                'value' => $value,
                'metadatable_id' => $this->getKey(),
                'metadatable_type' => get_class($this)
            ]
        );
    }

    public function getMeta($key, $userId, $default = null)
    {
        $meta = $this->metaData()->where('key', $key)->where('user_id', $userId)->first(['key', 'value']);
        return $meta ? $meta->value : $default;
    }

    public function deleteMeta($key, $userId)
    {
        return $this->metaData()->where('key', $key)->where('user_id', $userId)->delete();
    }

    public function getAllMetaForUser($userId)
    {
        return $this->metaData()->where('user_id', $userId)->get(['key', 'value']);
    }

    public function searchMeta($userId, $search)
    {
        return $this->metaData()
            ->where('user_id', $userId)
            ->where(function ($query) use ($search) {
                $query->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            })
            ->get(['key', 'value']);
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
