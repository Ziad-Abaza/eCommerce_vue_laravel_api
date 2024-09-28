<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Http\Resources\VendorResource;
use App\Traits\HandleFile;
use App\Traits\CrudOperationsTrait;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    use HandleFile, CrudOperationsTrait;

    public function index()
    {
        $vendors = $this->getAllRecords(new Vendor());
        return VendorResource::collection($vendors);
    }

    public function show($id)
    {
        $vendor = $this->findById(new Vendor(), $id);
        return new VendorResource($vendor);
    }

    public function store(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'password' => 'required|string|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) {
            return $validationError;
        }

        $data = $request->only(['name', 'email', 'password']);
        $data['password'] = bcrypt($data['password']);

        if ($request->hasFile('image')) {
            $data['image'] = $this->UploadFiles($request->file('image'), null, 'image');
        }

        $vendor = $this->createRecord(new Vendor(), $data);

        return new VendorResource($vendor);
    }

    public function update(Request $request, $id)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email,' . $id,
            'password' => 'nullable|string|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) {
            return $validationError;
        }

        $data = $request->only(['name', 'email']);
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $vendor = $this->findById(new Vendor(), $id);

        if ($request->hasFile('image')) {
            $data['image'] = $this->updateFile($request, 'image', $vendor->image, null, 'image');
        }

        $updatedVendor = $this->updateRecord(new Vendor(), $id, $data);

        return new VendorResource($updatedVendor);
    }

    public function destroy($id)
    {
        $this->deleteRecord(new Vendor(), $id, ['image']);
        return response()->json(['message' => 'Vendor deleted successfully.'], 200);
    }
}
