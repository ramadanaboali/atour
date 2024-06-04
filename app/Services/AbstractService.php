<?php

namespace App\Services;

class AbstractService
{
    protected $repo;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }
    public function get($id){
        return $this->repo->findOrFail($id);
    }
    public function getWithRelations($id,array $relations){
        return $this->repo->getWithRelations($id,$relations);
    }
    public function getAll(){
        return $this->repo->getAll();
    }
    public function store($data){

        $user=auth()->user();
        $data['created_by']= $user?->id;
        return $this->repo->create($data);
    }

    public function update($data,$item){
        $user=auth()->user();
        $data['updated_by']= $user?->id;
        return $this->repo->update($data,$item);
    }
    public function delete($item){
        return $this->repo->delete($item);
    }
    public function inputs(Array $request)
    {
        return [
            'limit' => $request['limit'] ?? 20,
            'offset' => $request['offset'] ?? 0,
            'sort' => $request['sort'] ?? 'ASC',
            'resource' => $request['resource'] ?? 'all',//[all,custom]
            'resource_columns' => $request['resource_columns'] ?? [],
            'field' => $request['field'] ?? 'id',
            'deleted' => $request['deleted'] ?? "all",//[all,deleted]
            'paginate' => $request['paginate'] ?? "true",
            'columns' => $request['columns'] ?? [],
            'operand' => $request['operand'] ?? [],
            'with' => $request['with'] ?? [],
            'has' => $request['has'] ?? null,
            'column_values' => $request['column_values'] ?? [],
        ];
    }

    public function whereOptions($input, $columns)
    {
        $wheres = [];
        $x = 0;
        foreach ($input["columns"] as $row) {
            if (in_array($row, array_values($columns))) {
                if (strtolower($input["operand"][$x]) == "like") {
                    $wheres[] = [$row, strtolower($input["operand"][$x]), '%' . $input["column_values"][$x] . '%'];
                } else {
                    $wheres[] = [$row, strtolower($input["operand"][$x]), $input["column_values"][$x]];
                }
                $x++;
            }
        }
        return $wheres;
    }

    public function Paginate(array $input, array $wheres,$soft_deleted = true, $model = null)
    {
        return $this->repo->Paginate($input, $wheres,$soft_deleted);
    }

    public function Meta($data, $input)
    {
        return $this->repo->Meta($data,$input);
    }
}
