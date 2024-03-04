<?php

namespace App\Services;

use App\Models\ApiResponse;
use Throwable;

/**
 * Provides a base service layer implementing common CRUD operations and more,
 * intended to be extended by specific service classes for different models.
 */
class BaseService
{
    /**
     * @var mixed The model class associated with the service.
     */
    protected $model;

    /**
     * @var mixed The resource class used for API resource transformations.
     */
    protected $resource;

    /**
     * @var array The relationships to be loaded with the model.
     */
    protected $relationships = [];

    /**
     * Retrieves a paginated list of model entries, including specified relationships.
     *
     * @return mixed A collection of model resources, paginated and transformed via API resource.
     */
    public function get()
    {
        $data = $this->model::latest()->with($this->relationships)->paged();

        return $this->resource::collection($data);
    }

    /**
     * Finds a single model entry by ID or slug, including specified relationships.
     *
     * @param string|null $id The ID of the model to find.
     * @param string|null $slug The slug of the model to find.
     * @return ApiResponse An ApiResponse instance containing the model found or a not found error.
     */
    public function findOne(string $id = null, string $slug = null)
    {
        $data = $this->model::with($this->relationships)
            ->when($id, fn ($query) => $query->whereId($id))
            ->when($slug, fn ($query) => $query->whereSlug($slug))
            ->first();

        if (!$data) {
            return new ApiResponse($this->getNotFoundMessage(), 404);
        }

        return new ApiResponse($this->modelName() . ' found', 200, $this->resource::make($data));
    }

    /**
     * Creates a new model entry with the provided payload.
     *
     * @param mixed $payload The data to create the model with.
     * @return ApiResponse An ApiResponse instance containing the created model or an error message.
     */
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

    /**
     * Updates an existing model instance identified by ID with the given payload.
     * Returns an ApiResponse with the updated model data or an error message.
     *
     * @param string $id The ID of the model to update.
     * @param mixed $payload Data to update the model instance.
     * @return ApiResponse
     */
    public function update(string $id, mixed $payload)
    {
        try {
            $data = $this->model::whereId($id)->first();
            if (!$data) {
                return new ApiResponse($this->getNotFoundMessage(), 404);
            }

            $data->update((array) $payload);

            return new ApiResponse($this->modelName() . ' updated', 200, $this->resource::make($data));
        } catch (Throwable $e) {
            return new ApiResponse('Sorry, unable to update ' . $this->modelName(), 500);
        }
    }

    /**
     * Deletes a model instance identified by ID.
     * Returns an ApiResponse indicating the deletion status.
     *
     * @param mixed $id The ID of the model to delete.
     * @return ApiResponse
     */
    public function delete(mixed $id)
    {
        try {
            $data = $this->model::whereId($id)->first();
            if (!$data) {
                return new ApiResponse($this->getNotFoundMessage(), 404);
            }

            $data->delete();

            return new ApiResponse($this->modelName() . ' deleted', 200);
        } catch (Throwable $e) {
            return new ApiResponse('Sorry, you are unable to delete this record', 400);
        }
    }

    /**
     * Updates the active status of a model instance identified by ID.
     * Returns an ApiResponse indicating the update status.
     *
     * @param mixed $id The ID of the model to update.
     * @param bool $active The new active status.
     * @return ApiResponse
     */
    public function changeActive(mixed $id, bool $active)
    {
        $data = $this->model::find($id);
        if (!$data) {
            return new ApiResponse($this->getNotFoundMessage(), 404);
        }

        $data->update(['is_active' => $active]);

        return new ApiResponse($this->modelName() . ' status updated', 200, $this->resource::make($data));
    }

    /**
     * Generates a not found message based on the model name.
     *
     * @return string The not found message.
     */
    private function getNotFoundMessage()
    {
        return $this->modelName() . ' not found';
    }

    /**
     * Determines the model name based on the class name.
     * Splits camel case to separate words.
     *
     * @return string The model name in a readable format.
     */
    private function modelName()
    {
        $explode = explode('\\', $this->model);

        $word = $explode[2];
        $regX = '/(?(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z]))/x';
        $array = preg_split($regX, $word);

        return ucfirst(strtolower(implode(' ', $array)));
    }
}
