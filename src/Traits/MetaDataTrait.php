<?php

namespace Towoju5\SmartMetaManager\Traits;

use Towoju5\SmartMetaManager\Models\MetaDataModel;

trait MetaDataTrait
{
    public function metaData()
    {
        return $this->morphMany(MetaDataModel::class, 'metadatable');
    }

    public function setMeta($key, $value)
    {
        return $this->metaData()->updateOrCreate(
            [
                'key' => $key,
                'metadatable_type' => get_class($this),
                'metadatable_id' => $this->id,
                'user_id' => auth()->user()->id,
            ],
            ['value' => $value]
        );
    }

    public function getMeta($key, $default = null)
    {
        $meta = $this->metaData()
            ->where('key', $key)
            ->where('metadatable_type', get_class($this))
            ->where('metadatable_id', $this->id)
            ->first();
        return $meta ? $meta->value : $default;
    }

    public function deleteMeta($key)
    {
        return $this->metaData()
            ->where('key', $key)
            ->where('metadatable_type', get_class($this))
            ->where('metadatable_id', $this->id)
            ->delete();
    }

    public function getAllMeta()
    {
        return $this->metaData()
            ->where('metadatable_type', get_class($this))
            ->where('metadatable_id', $this->id)
            ->get();
    }

    public function searchMeta($search)
    {
        return $this->metaData()
            ->where('metadatable_type', get_class($this))
            ->where('metadatable_id', $this->id)
            ->where(function ($query) use ($search) {
                $query->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            })
            ->get();
    }

    public function hasMetaKeyValue($key, $value)
    {
        return $this->metaData()
            ->where('key', $key)
            ->where('value', $value)
            ->where('metadatable_type', get_class($this))
            ->where('metadatable_id', $this->id)
            ->exists();
    }
}
