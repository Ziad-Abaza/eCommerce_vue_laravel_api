<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;

trait CrudOperationsTrait
{
    /*
    |--------------------------------------------------------------------------
    | Validate Request Data
    |--------------------------------------------------------------------------
    */
    public function validateRequestData($request, $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        return null;  
    }

    /*
    |--------------------------------------------------------------------------
    | Retrieve all records with relation
    |--------------------------------------------------------------------------
    */
    public function getAllWithRelation(Model $model, array $relation, array $columns = ['*'])
    {
        return $model::with($relation)->get($columns);
    }

    /*
    |--------------------------------------------------------------------------
    | Retrieve all records
    |--------------------------------------------------------------------------
    */
    public function getAllRecords(Model $model, array $columns = ['*'])
    {
        return $model::all($columns);
    }

    /*
    |--------------------------------------------------------------------------
    | Find record with relation by ID
    |--------------------------------------------------------------------------
    */
    public function findWithRelation(Model $model, array $relation, int $id)
    {
        return $model::with($relation)->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Find record by ID
    |--------------------------------------------------------------------------
    */
    public function findById(Model $model, int $id)
    {
        return $model::findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Create a new record
    |--------------------------------------------------------------------------
    */
    public function createRecord(Model $model, array $data)
    {
        return $model::create($data);
    }

    /*
    |--------------------------------------------------------------------------
    | Update an existing record
    |--------------------------------------------------------------------------
    */
    public function updateRecord(Model $model, int $id, array $data)
    {
        $record = $this->findById($model, $id);
        $record->update($data);
        return $record;
    }

    /*
    |--------------------------------------------------------------------------
    | Delete a record
    |--------------------------------------------------------------------------
    */
    public function deleteRecord(Model $model, int $id, array $fileFields = [])
    {
        $record = $this->findById($model, $id);

        foreach ($fileFields as $fileField) {
            $filePath = $record->$fileField;
            $this->deleteFile($filePath);
        }

        $record->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Function to Determine Folder
    |--------------------------------------------------------------------------
    */
    private function findFolder(string $fileName): string
    {
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $folders = [
            'jpg' => 'images', 'jpeg' => 'images', 'png' => 'images', 'svg' => 'images',
            'mp4' => 'video', 'pdf' => 'files', 'doc' => 'files', 'docx' => 'files',
            'txt' => 'files', 'xls' => 'files', 'xlsx' => 'files',
        ];

        return $folders[$fileExtension] ?? 'files';
    }

    /*
    |--------------------------------------------------------------------------
    | Get Foreign Key Value
    |--------------------------------------------------------------------------
    */
    public function getForeignKey(Model $model, string $foreignKey, int $id)
    {
        return $model::where("id", $id)->pluck($foreignKey)->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Delete File from Disk
    |--------------------------------------------------------------------------
    */
    public function deleteFile(string $path)
    {
        $fileName = basename($path);
        $folder = $this->findFolder($fileName);
        $filePath = public_path("assets/{$folder}/{$fileName}");

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
