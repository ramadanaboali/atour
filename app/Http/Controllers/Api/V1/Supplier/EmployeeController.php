<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\EmployeeRequest;
use App\Http\Requests\Vendor\EmployeeServiceRequest;
use App\Models\Employee;
use App\Services\General\StorageService;
use App\Services\Vendor\EmployeeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use function response;

class EmployeeController extends Controller
{
    protected EmployeeService $service;
    protected StorageService $storageService;
    public function __construct(EmployeeService $service,StorageService $storageService)
    {
        $this->storageService = $storageService;
        $this->service = $service;

    }
    public function index(PaginateRequest $request)
    {
        $input = $this->service->inputs($request->all());
        $model = new Employee();
        $columns = Schema::getColumnListing($model->getTable());

        if (count($input["columns"]) < 1 || (count($input["columns"]) != count($input["column_values"])) || (count($input["columns"]) != count($input["operand"]))) {
            $wheres = [];
        } else {
            $wheres = $this->service->whereOptions($input, $columns);

        }
        $data = $this->service->Paginate($input, $wheres);
        //$meta = $this->service->Meta($data,$input);
        return response()->apiSuccess($data);
    }

    public function show($id){
        return response()->apiSuccess($this->service->get($id));
    }

    public function store(EmployeeRequest $request)
    {
        $data = $request->except(['image','images','officialHours']);
        $folder_path = "images/employee";
        $storedPath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data['image'] = $storedPath;
        $data['vendor_id'] = auth()->user()->model_id;
        DB::beginTransaction();
        $employee=$this->service->createItem($data);
        if ($employee && $request->officialHours) {
            $this->service->officialHours($request->officialHours,$employee->id);
        }
        if ($employee && $request->breakHours) {
            $this->service->breakHours($request->breakHours,$employee->id);
        }
        DB::commit();
        return response()->apiSuccess($employee);
    }
    public function employeeService(EmployeeServiceRequest $request)
    {
        $data = $request->all();
        return response()->apiSuccess($this->service->employeeService($data));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {

        $data = $request->except(['image','images','officialHours']);
        $folder_path = "images/Employee";
        $storedPath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data['image'] = $storedPath;

        if ($employee && $request->officialHours) {
            if($employee->officialHours()){

                // $employee->officialHours()->delete();
            }
            $this->service->officialHours($request->officialHours, $employee->id);
        }
        if ($employee && $request->breakHours) {
            if($employee->breakHours()){
                // $employee->breakHours()->delete();
            }
            $this->service->breakHours($request->breakHours, $employee->id);
        }

        return response()->apiSuccess($this->service->updateItem($data,$employee));
    }
    public function delete(Employee $employee)
    {
        return response()->apiSuccess($this->service->delete($employee));
    }

}
