<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Crudable
{
    protected function _index()
    {
        $data = $this->model::latest()->paged();

        return $this->modelResource::collection($data);
    }

    protected function _store(Request $request, $message = null)
    {
        $data = $this->model::create($request->all());

        return response()->json([
            'message' => $message === null ? $this->modelName() . ' created successfully' : $message,
            'data' => new $this->modelResource($data->fresh()),
        ], 200);
    }

    protected function _update(Request $request, $id)
    {
        $data = $this->model::find($id);
        $data->update($request->all());

        return response()->json([
            'message' => $this->modelName() . ' updated successfully',
            'data' => new $this->modelResource($data->fresh()),
        ], 200);
    }

    protected function _show($id)
    {
        $data = $this->model::findorFail($id);

        return new $this->modelResource($data);
    }

    protected function _destroy($id)
    {
        $data = $this->model::findorFail($id);
        $data->delete();

        return response()->json(['message' => $this->modelName() . ' deleted successfully'], 200);
    }

    protected function _toggleStatus($id)
    {
        $data = $this->model::findorFail($id);
        $data->is_active = $data->is_active ? 0 : 1;
        $data->save();

        return response()->json([
            'message' => 'Status changed successfully',
            'data' => new $this->modelResource($data),
        ], 200);
    }

    private function modelName()
    {
        $explode = explode('\\', $this->model);

        $word = $explode[2];
        $regX = '/(?(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z]))/x';
        $array = preg_split($regX, $word);

        return ucfirst(strtolower(implode(' ', $array)));
    }
}
