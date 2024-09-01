<?php

namespace Towoju5\SmartMetaManager;

use App\Modules\user\Models\User;
use Request;
use Towoju5\SmartMetaManager\Models\MetaDataModel;

class SmartMetaManager
{
    public function index(Request $request, $model, $id)
    {
        auth()->login(User::first());
        $modelClass = $this->getModelClass($model);
        $instance = $modelClass::findOrFail($id);
        $userId = $request->user()->id;
        return response()->json($instance->metaData()->where('user_id', $userId)->get());
    }

    public function store(Request $request, $model, $id)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        $modelClass = $this->getModelClass($model);
        $instance = $modelClass::findOrFail($id);
        $userId = $request->user()->id;
        $meta = $instance->setMeta($request->key, $request->value, $userId);

        return response()->json($meta, 201);
    }

    public function show(Request $request, $model, $id, $key)
    {
        $modelClass = $this->getModelClass($model);
        $instance = $modelClass::findOrFail($id);
        $userId = $request->user()->id;
        $value = $instance->getMeta($key, $userId);

        return response()->json(['key' => $key, 'value' => $value]);
    }

    public function update(Request $request, $model, $id, $key)
    {
        $request->validate([
            'value' => 'required|string',
        ]);

        $modelClass = $this->getModelClass($model);
        $instance = $modelClass::findOrFail($id);
        $userId = $request->user()->id;
        $meta = $instance->setMeta($key, $request->value, $userId);

        return response()->json($meta);
    }

    public function destroy($model, $id, $key)
    {
        $modelClass = $this->getModelClass($model);
        $instance = $modelClass::findOrFail($id);
        $userId = request()->user()->id;
        $instance->deleteMeta($key, $userId);

        return response()->json(null, 204);
    }

    public function getUserMeta(Request $request)
    {
        $userId = $request->user()->id;
        $metaData = MetaDataModel::where('user_id', $userId)->get();

        $groupedMeta = $metaData->groupBy(function ($item) {
            return get_class($item->metadatable) . '|' . $item->metadatable_id;
        });

        $result = [];
        foreach ($groupedMeta as $key => $group) {
            list($modelClass, $modelId) = explode('|', $key);
            $shortName = array_search($modelClass, config('meta_models'));
            $result[] = [
                'model' => $shortName,
                'id' => $modelId,
                'meta' => $group->map(function ($item) {
                    return ['key' => $item->key, 'value' => $item->value];
                }),
            ];
        }

        return response()->json($result);
    }

    private function getModelClass($shortName)
    {
        $modelClass = config("meta_models.{$shortName}");

        if (!$modelClass) {
            abort(404, "Model not found for '{$shortName}'");
        }

        return $modelClass;
    }
}

