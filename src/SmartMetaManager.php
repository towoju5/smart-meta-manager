<?php

namespace Towoju5\SmartMetaManager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmartMetaManager
{
    public function getModelMeta(Request $request, $model)
    {
        try {
            $modelClass = $this->getModelClass($model);
            $userId = Auth::id();
            $modelInstance = new $modelClass();
            $meta = $modelInstance->getAllMetaForUser($userId);
            return $this->api_success_response('Meta data retrieved successfully', $meta);
        } catch (\Exception $e) {
            return $this->api_error_response('Error retrieving meta data', ['error' => $e->getMessage()]);
        }
    }

    public function getAllMeta(Request $request)
    {
        try {
            $userId = Auth::id();
            $allMeta = [];
            foreach (config('meta_models.meta_data_models') as $model => $class) {
                $modelInstance = new $class();
                $modelMeta = $modelInstance->getAllMetaForUser($userId);
                if ($modelMeta->isNotEmpty()) {
                    $allMeta[$model] = $modelMeta;
                }
            }
            return $this->api_success_response('All user meta data retrieved successfully', $allMeta);
        } catch (\Exception $e) {
            return $this->api_error_response('Error retrieving all user meta data', ['error' => $e->getMessage()]);
        }
    }

    public function getAllUserMeta()
    {
        return $this->getAllMeta(request());
    }

    public function searchMeta(Request $request, $model)
    {
        try {
            if (!$request->has('q')) {
                return $this->api_error_response('Error search query not provided', ['error' => "Error search query not provided"]);
            }
            $modelClass = $this->getModelClass($model);
            $userId = Auth::id();
            $search = $request->input('q');
            $modelInstance = new $modelClass();
            $meta = $modelInstance->searchMeta($search);
            if (empty($meta) || $meta === null || is_countable($meta) && count($meta) < 1) {
                return $this->api_error_response('Meta data not found', ['key' => $search], 404);
            }
            return $this->api_success_response('Meta data search results', $meta);
        } catch (\Exception $e) {
            return $this->api_error_response('Error searching meta data', ['error' => $e->getMessage()]);
        }
    }

    public function checkMetaKeyValue(Request $request, $model)
    {
        try {
            $validated = $request->validate([
                'key' => 'required|string',
                'value' => 'required|string',
            ]);

            $modelClass = $this->getModelClass($model);
            $userId = Auth::id();
            $modelInstance = new $modelClass();
            $meta = $modelInstance->hasMetaKeyValue($validated['key'], $validated['value'], $userId);

            return $this->api_success_response(
                $meta ? 'Meta key-value pair exists' : 'Meta key-value pair does not exist',
                ['exists' => $meta]
            );
        } catch (\Exception $e) {
            return $this->api_error_response('Error checking meta key-value pair', ['error' => $e->getMessage()]);
        }
    }


    public function setMeta(Request $request, $model)
    {
        try {
            $validated = $request->validate([
                'key' => 'required|string',
                'value' => 'required|string',
            ]);

            $modelClass = $this->getModelClass($model);
            $userId = Auth::id();
            $modelInstance = new $modelClass();
            $meta = $modelInstance->setMeta($validated['key'], $validated['value'], $userId);

            return $this->api_success_response('Meta data stored successfully', $meta, 201);
        } catch (\Exception $e) {
            return $this->api_error_response('Error storing meta data', ['error' => $e->getMessage()]);
        }
    }
    public function getMeta(Request $request, $model, $key)
    {
        try {
            $modelClass = $this->getModelClass($model);
            $userId = Auth::id();
            $modelInstance = new $modelClass();
            $meta = $modelInstance->getMeta($key);
            // var_dump($meta); exit;
            if (empty($meta) || $meta === null) {
                // Add debugging statements
                \Log::debug('Meta value:', ['meta' => $meta]);
                \Log::debug('Meta type:', ['type' => gettype($meta)]);

                if ($meta instanceof \Illuminate\Support\Collection) {
                    \Log::debug('Meta is a Collection. Is empty?', ['isEmpty' => $meta->isEmpty()]);
                }

                return $this->api_error_response('Meta data not found', ['key' => $key], 404);
            }

            return $this->api_success_response('Meta data retrieved successfully', ['key' => $key, 'value' => $meta]);
        } catch (\Exception $e) {
            return $this->api_error_response('Error retrieving meta data', ['error' => $e->getMessage()]);
        }
    }

    public function updateMeta(Request $request, $model, $key)
    {
        try {
            $validated = $request->validate([
                'value' => 'required|string',
            ]);

            $modelClass = $this->getModelClass($model);
            $userId = Auth::id();
            $modelInstance = new $modelClass();
            $meta = $modelInstance->setMeta($key, $validated['value'], $userId);

            return $this->api_success_response('Meta data updated successfully', $meta);
        } catch (\Exception $e) {
            return $this->api_error_response('Error updating meta data', ['error' => $e->getMessage()]);
        }
    }

    public function deleteMeta(Request $request, $model, $key)
    {
        try {
            $modelClass = $this->getModelClass($model);
            $userId = Auth::id();
            $modelInstance = new $modelClass();
            $meta = $modelInstance->deleteMeta($key, $userId);

            return $this->api_success_response('Meta data deleted successfully');
        } catch (\Exception $e) {
            return $this->api_error_response('Error deleting meta data', ['error' => $e->getMessage()]);
        }
    }

    private function getModelClass($shortName)
    {
        $modelClass = config("meta_models.meta_data_models.{$shortName}");

        if (!$modelClass) {
            throw new \Exception("Model not found for '{$shortName}'");
        }

        return $modelClass;
    }

    public function api_success_response($message, $data = null, $code = 200)
    {
        return response()->json([
            'status' => true,
            "status_code" => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function api_error_response(string $message = '', array $errors = [], string|int $code = 400)
    {
        $response = [
            "status" => false,
            "status_code" => $code,
            "message" => $message,
            "errors" => $errors instanceof \Illuminate\Support\MessageBag ? $errors : new \Illuminate\Support\MessageBag($errors)
        ];

        return response()->json($response, $code);
    }
}
