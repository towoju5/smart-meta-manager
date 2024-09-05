<?php

namespace Towoju5\SmartMetaManager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmartMetaManager
{
    public function __construct()
    {
        if(auth()->check()) {
            echo api_error_response("Unauthorized", ['error' => 'Please login to continue', 401]); exit;
        }
    }


    public function getModelMeta(Request $request, $model)
    {
        try {
            $modelClass = $this->getModelClass($model);
            $userId = Auth::id();
            $allMeta = $modelClass::getAllMetaForUser($userId);
            return $this->api_success_response('Meta data retrieved successfully', $allMeta);
        } catch (\Exception $e) {
            return $this->api_error_response('Error retrieving meta data', $e->getMessage());
        }
    }

    public function getAllUserMeta(Request $request)
    {
        try {
            $userId = Auth::id();
            $allMeta = [];
            foreach (config('meta_models.meta_data_models') as $model => $class) {
                $modelMeta = $class::getAllMetaForUser($userId);
                if ($modelMeta->isNotEmpty()) {
                    $allMeta[$model] = $modelMeta;
                }
            }
            return $this->api_success_response('All user meta data retrieved successfully', $allMeta);
        } catch (\Exception $e) {
            return $this->api_error_response('Error retrieving all user meta data', $e->getMessage());
        }
    }

    public function searchMeta(Request $request, $model)
    {
        try {
            $modelClass = $this->getModelClass($model);
            $userId = Auth::id();
            $search = $request->input('search');
            $results = $modelClass::searchMeta($userId, $search);
            return $this->api_success_response('Meta data search results', $results);
        } catch (\Exception $e) {
            return $this->api_error_response('Error searching meta data', $e->getMessage());
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
            $exists = $modelClass::hasMetaKeyValue($validated['key'], $validated['value'], $userId);

            return $this->api_success_response(
                $exists ? 'Meta key-value pair exists' : 'Meta key-value pair does not exist',
                ['exists' => $exists]
            );
        } catch (\Exception $e) {
            return $this->api_error_response('Error checking meta key-value pair', $e->getMessage());
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
            $meta = $modelClass::setMeta($validated['key'], $validated['value'], $userId);

            return $this->api_success_response('Meta data stored successfully', $meta, 201);
        } catch (\Exception $e) {
            return $this->api_error_response('Error storing meta data', $e->getMessage());
        }
    }

    public function getMeta(Request $request, $model, $key)
    {
        try {
            $modelClass = $this->getModelClass($model);
            $userId = Auth::id();
            $value = $modelClass::getMeta($key, $userId);

            return $this->api_success_response('Meta data retrieved successfully', ['key' => $key, 'value' => $value]);
        } catch (\Exception $e) {
            return $this->api_error_response('Error retrieving meta data', $e->getMessage());
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
            $meta = $modelClass::setMeta($key, $validated['value'], $userId);

            return $this->api_success_response('Meta data updated successfully', $meta);
        } catch (\Exception $e) {
            return $this->api_error_response('Error updating meta data', $e->getMessage());
        }
    }

    public function deleteMeta(Request $request, $model, $key)
    {
        try {
            $modelClass = $this->getModelClass($model);
            $userId = Auth::id();
            $modelClass::deleteMeta($key, $userId);

            return $this->api_success_response('Meta data deleted successfully');
        } catch (\Exception $e) {
            return $this->api_error_response('Error deleting meta data', $e->getMessage());
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

    private function api_success_response($message, $data = null, $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    private function api_error_response($message, $errors = null, $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}
