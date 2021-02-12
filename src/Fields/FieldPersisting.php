<?php

namespace Arniro\Admin\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait FieldPersisting
{
    protected $storing = true;
    protected $updating = true;

    protected $storeCallback;
    protected $storeSavedCallback;
    protected $updateCallback;
    protected $destroyCallback;

    /**
     * Update a field value of the model before updating in the database.
     *
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function update(Request $request, Model $model)
    {
        return $this->persist('update', $request, $model);
    }

    /**
     * Update a field value of the model before storing to the database.
     *
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function store(Request $request, Model $model)
    {
        return $this->persist('store', $request, $model);
    }

    /**
     * Update a field value of the model after storing to the database.
     *
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function storeSaved(Request $request, Model $model)
    {
        return $this->persistWithoutDefault('storeSaved', $request, $model);
    }

    /**
     * Call an action before deleting an entity from the database.
     *
     * @param Model $model
     * @return mixed
     */
    public function destroy(Model $model)
    {
        return $this->persistWithoutDefault('destroy', null, $model);
    }

    /**
     * Set a callback for updating a field value of the model
     * before updating in the database.
     *
     * @param $callback
     * @return $this
     */
    public function updateVia($callback)
    {
        $this->updateCallback = $callback;

        return $this;
    }

    /**
     * Set a callback for updating a field value of the model
     * before storing to the database.
     *
     * @param $callback
     * @return $this
     */
    public function storeVia($callback)
    {
        $this->storeCallback = $callback;

        return $this;
    }

    /**
     * Set a callback for updating a field value of the model
     * after storing to the database.
     *
     * @param $callback
     * @return $this
     */
    public function storeSavedVia($callback)
    {
        $this->storeSavedCallback = $callback;

        return $this;
    }

    /**
     * Set a callback that will be called
     * before deleting an entity from the database.
     *
     * @param $callback
     * @return $this
     */
    public function destroyVia($callback)
    {
        $this->destroyCallback = $callback;

        return $this;
    }

    /**
     * Set a single callback for updating a field value of the model
     * before storing and updating a model.
     *
     * @param $callback
     * @return FieldPersisting
     */
    public function saveVia($callback)
    {
        return $this->storeVia($callback)
            ->updateVia($callback);
    }

    /**
     * Determine whether the field should update the model or not.
     *
     * @return bool
     */
    public function shouldStore()
    {
        return $this->storing;
    }

    /**
     * Determine whether the field should store the model or not.
     *
     * @return bool
     */
    public function shouldUpdate()
    {
        return $this->updating;
    }

    /**
     * Specify that the value of the field
     * should not be stored to the database.
     *
     * @return $this
     */
    public function disableStoring()
    {
        $this->storing = false;

        return $this;
    }

    /**
     * Specify that the value of the field
     * should not be updated in the database.
     *
     * @return $this
     */
    public function disableUpdating()
    {
        $this->updating = false;

        return $this;
    }

    /**
     * Specify that the value of the field
     * should not be persisted to the database.
     *
     * @return $this
     */
    public function disableSaving()
    {
        $this->storing = false;
        $this->updating = false;

        return $this;
    }

    /**
     * Update a field value of the model before/after persisting to the database.
     *
     * @param string $method
     * @param Request $request
     * @param Model $model
     * @param bool $withDefault
     * @return mixed
     */
    protected function persist($method, ?Request $request, Model $model, $withDefault = true)
    {
        $parameters = $request ? ['request' => $request, 'model' => $model] : ['model' => $model];

        if ($callback = $this->{$method . 'Callback'}) {
            return app()->call($callback, $parameters);
        }

        if (method_exists($this, $name = $method . class_basename($this))) {
            return app()->call([$this, $name], $parameters);
        }

        if (method_exists($this, $name = $method . 'Model')) {
            return app()->call([$this, $name], $parameters);
        }

        if (! $withDefault) return null;

        return $model->setAttribute($this->attribute, $this->normalize($request[$this->attribute]));
    }

    /**
     * @param $method
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    protected function persistWithoutDefault($method, ?Request $request, Model $model)
    {
        return $this->persist($method, $request, $model, false);
    }
}
