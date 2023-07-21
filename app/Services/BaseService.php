<?php

namespace App\Services;

use App\Models\ApiResponse;
use Throwable;

class BaseService
{
    protected $model;

    protected $resource;

    protected $relationships = [];

    public function get()
    {
        $data = $this->model::latest()->with($this->relationships)->paged();

        return $this->resource::collection($data);
    }

    public function findOne(string $id)
    {
        $data = $this->model::with($this->relationships)->whereId($id)->orWhere('slug', $id)->first();
        if (!$data) {
            return new ApiResponse($this->getNotFoundMessage(), 404);
        }

        return new ApiResponse($this->modelName() . ' found', 200, $this->resource::make($data));
    }

    public function create(mixed $payload)
    {
        try {
            $data = $this->model::create((array) $payload);

            return new ApiResponse($this->modelName() . ' created', 200, $this->resource::make($data->fresh()->load($this->relationships)));
        } catch (Throwable $e) {
            logger("Error creating {$this->modelName()}: {$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
            logger($e->getTraceAsString());

            return new ApiResponse('Sorry, unable to create ' . $this->modelName(), 500);
        }
    }

    public function update(string $id, mixed $payload)
    {
        try {
            $data = $this->model::whereId($id)->orWhere('slug', $id)->first();
            if (!$data) {
                return new ApiResponse($this->getNotFoundMessage(), 404);
            }

            $data->update((array) $payload);

            return new ApiResponse($this->modelName() . ' updated', 200, $this->resource::make($data));
        } catch (Throwable $e) {
            return new ApiResponse('Sorry, unable to update ' . $this->modelName(), 500);
        }
    }

    public function delete(mixed $id)
    {
        try {
            $data = $this->model::whereId($id)->orWhere('slug', $id)->first();
            if (!$data) {
                return new ApiResponse($this->getNotFoundMessage(), 404);
            }

            $data->delete();

            return new ApiResponse($this->modelName() . ' deleted', 200);
        } catch (Throwable $e) {
            return new ApiResponse('Sorry, you are unable to delete this record', 400);
        }
    }

    public function changeActive(mixed $id, bool $active)
    {
        $data = $this->model::find($id);
        if (!$data) {
            return new ApiResponse($this->getNotFoundMessage(), 404);
        }

        $data->update(['is_active' => $active]);

        return new ApiResponse($this->modelName() . ' status updated', 200, $this->resource::make($data));
    }

    private function getNotFoundMessage()
    {
        return $this->modelName() . ' not found';
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
