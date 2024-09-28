<?php

namespace Towoju5\SmartMetaManager\Trait;

use Towoju5\SmartMetaManager\Models\MetaDataModel;

trait MetaDataTrait
{
    public function setMeta($key, $value)
    {
        return MetaDataModel::updateOrCreate(
            [
                'key' => $key,
                'metadatable_type' => get_class($this),
                'user_id' => auth()->id(),
            ],
            ['value' => $value]
        );
    }

    public function getMeta($key, $default = null)
    {
        $meta = MetaDataModel::where('key', $key)
            ->where('metadatable_type', get_class($this))
            ->where('user_id', auth()->id())
            ->first();
        return $meta ? $meta->value : $default;
    }

    public function getAllMetaForUser()
    {
        return MetaDataModel::where('metadatable_type', get_class($this))
            ->where('user_id', auth()->id())
            ->get();
    }


    public function deleteMeta($key)
    {
        return MetaDataModel::where('key', $key)
            ->where('metadatable_type', get_class($this))
            ->where('user_id', auth()->id())
            ->delete();
    }

    public function getAllMeta()
    {
        return MetaDataModel::where('metadatable_type', get_class($this))
            ->where('user_id', auth()->id())
            ->get();
    }

    public function searchMeta($search)
    {
        return MetaDataModel::where('metadatable_type', get_class($this))
            ->where('user_id', auth()->id())
            ->where(function ($query) use ($search) {
                $query->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            })
            ->get();
    }

    public function hasMetaKeyValue($key, $value)
    {
        return MetaDataModel::where('key', $key)
            ->where('value', $value)
            ->where('metadatable_type', get_class($this))
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function whereMeta($conditions)
    {
        $query = MetaDataModel::where('user_id', auth()->id())
            ->where('metadatable_type', get_class($this));
        foreach ($conditions as $key => $value) {
            $query->where('key', $key)->where('value', $value);
        }
        return $query->get();
    }
}
