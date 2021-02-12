<?php

namespace Arniro\Admin\Http\Controllers;

use Arniro\Admin\Fields\BelongsToMany;
use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Http\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class PivotsController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var AdminRequest
     */
    protected $request;

    public function create($resource, $id, $relationship, AdminRequest $request)
    {
        $resource = $request->resource($resource, $id);
        $viaResourceField = $this->withRequest($request)->field($resource, $relationship);

        $request->resource($relationship)
            ->setVia($resource, $viaResourceField)
            ->authorize('attach');

        return [
            'label' => $resource->label(),
            'name' => $resource->resourceName(),
            'icon' => 'pin',
            'fields' => $this->withRequest($request)
                ->field($resource, $relationship)
                ->getFields()
        ];
    }

    public function store($resource, $id, $relationship, AdminRequest $request)
    {
        $resource = $request->resource($resource, $id);

        $viaResourceField = $this->withRequest($request)->field($resource, $relationship);

        $request->resource($relationship)
            ->setVia($resource, $viaResourceField)
            ->authorize('attach');

        $model = $resource->resource();
        $method = $request->input('viaRelationship');

        $field = $this->withRequest($request)->field($resource, $relationship);

        $attachingResource = $request->resource($relationship, $request->input($field->attribute));

        [$rules, $additionalData] = $this->extraFieldsFor($field);

        $request->validate(array_merge([
            $field->attribute => [
                'required',
                function ($attribute, $value, $fail) use ($resource) {
                    $this->validatePivotUniqueness($resource, $value, $fail);
                }
            ],
        ], $rules));

        $model->$method()->attach($request->only($field->attribute), $additionalData);

        $this->triggerPostAttachedHook($resource, $attachingResource);
    }

    public function edit($resource, $id, $relationship, $attachId, AdminRequest $request)
    {
        $attachingResourceName = $request->resourceClass($relationship);
        $resource = $request->resource($resource, $id);

        $methodName = $request->input('viaRelationship');
        $attachingMethod = $resource->resource()->$methodName();

        $attachingResource = new $attachingResourceName($attachingMethod->find($attachId));
        $viaResourceField = $this->withRequest($request)->field($resource, $relationship);

        $attachingResource->setVia($resource, $viaResourceField)
            ->authorize('attach');

        return [
            'label' => $resource->label(),
            'name' => $resource->resourceName(),
            'icon' => 'pin',
            'fields' => $this->withRequest($request)
                ->field($resource, $relationship)
                ->getFields($attachingResource)
        ];
    }

    public function update($resource, $id, $relationship, $attachId, AdminRequest $request)
    {
        $resource = $request->resource($resource, $id);
        $model = $resource->resource();
        $method = $request->input('viaRelationship');

        $viaResource = $request->resource($relationship, $attachId);
        $attached = $viaResource->resource();
        $field = $this->withRequest($request)->field($resource, $relationship);

        $viaResource->setVia($resource, $field)
            ->authorize('attach');

        [$rules, $additionalData] = $this->extraFieldsFor($field);

        $request->validate(array_merge([
            $field->attribute => [
                'required',
                function ($attribute, $value, $fail) use ($resource, $attached) {
                    $this->validatePivotUniqueness($resource, $value, $fail, $attached);
                }
            ],
        ], $rules));

        if (! empty($additionalData)) {
            $model->$method()->updateExistingPivot($request->input($field->attribute), $additionalData);
        }
    }

    public function destroy($resource, $id, $relationship, $attachId, AdminRequest $request)
    {
        $viaModel = $request->model($relationship, $attachId);
        $method = $request->input('viaRelationship');

        $viaModel->$method()->detach($id);

        return $request->resource($resource)->fetch($viaModel->$method());
    }

    /**
     * @param AdminRequest $request
     * @return $this
     */
    protected function withRequest(AdminRequest $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Resolves BelongsToMany field from the resource
     * @param Resource $resource
     * @param string $relationship
     * @return BelongsToMany
     */
    protected function field($resource, $relationship)
    {
        return $resource->resolveField(
            $this->request->input('viaRelationship'),
            $this->request->resourceClass($relationship)
        );
    }

    /**
     * Validate that the pivot is unique
     *
     * @param Resource $resource
     * @param $value
     * @param $fail
     * @param Model $attached
     */
    protected function validatePivotUniqueness(Resource $resource, $value, $fail, ?Model $attached = null)
    {
        $method = $this->request->viaRelationship($resource->resourceName(), $resource->resource()->id);

        $exists = $method->when($attached, function ($query, $attached) use ($method, $value) {
            return $method->wherePivot($method->getRelatedPivotKeyName(), '!=', $attached->id);
        })->wherePivot($method->getRelatedPivotKeyName(), $value)->exists();

        if ($exists) {
            $fail('Record already exists.');
        }
    }

    /**
     * Get rules and additional data from any fields
     * which may be defined in BelongsToMany field.
     *
     * @param BelongsToMany $field
     * @return array
     */
    protected function extraFieldsFor($field)
    {
        $rules = [];
        $additionalData = [];

        foreach ($field->getExtraFields() as $additionalField) {
            $additionalData[$additionalField->attribute] = $this->request->input($additionalField->attribute);
            $rules[$additionalField->attribute] = $additionalField->getCreationRules();
        }

        return [$rules, $additionalData];
    }

    /**
     * @param Resource $resource
     * @param Resource $viaResource
     * @return mixed
     */
    protected function triggerPostAttachedHook($resource, $viaResource)
    {
        if ($method = $resource->suggestions()->hasPostAttachedMethod($viaResource)) {
            return $resource->$method($viaResource);
        }
    }
}
