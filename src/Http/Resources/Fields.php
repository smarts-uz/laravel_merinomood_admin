<?php

namespace Arniro\Admin\Http\Resources;

use Arniro\Admin\Exceptions\ResourceViewNotFoundException;
use Arniro\Admin\Fields\Field;
use Arniro\Admin\Fields\Relationship;
use Arniro\Admin\Support\Collection;

trait Fields
{
    abstract public function fields();

    protected function simpleFields()
    {
        return array_filter($this->fields(), function ($field) {
            return !$field instanceof Relationship;
        });
    }

    protected function storingFields()
    {
        return array_filter($this->simpleFields(), function (Field $field) {
            return $field->shouldStore();
        });
    }

    protected function updatingFields()
    {
        return array_filter($this->simpleFields(), function (Field $field) {
            return $field->shouldUpdate();
        });
    }

    protected function relationships()
    {
        return collect($this->getCurrentViewFields())->filter(function (Field $field) {
            return $field instanceof Relationship;
        })->map(function (Field $field) {
            return $field->toResponse($this);
        })->values()->toArray();
    }

    public function getFields()
    {
        if ($this->via && $this->viaField) {
            return (new Collection($this->viaField->getFields($this)))->toArray();
        }

        return (new Collection($this->getCurrentViewFields()))
            ->filter(function (Field $field) {
                return !$field instanceof Relationship;
            })->map(function (Field $field) {
                return $field->toResponse($this);
            })->toArray();
    }

    protected function getCurrentViewFields()
    {
        if (!method_exists($this, $methodName = $this->view . 'Fields')) {
            throw new ResourceViewNotFoundException('Resource view cannot be found.');
        }

        return $this->$methodName();
    }

    protected function fieldsForView($view)
    {
        return array_values(
            array_filter($this->fields(), function (Field $field) use ($view) {
                return $field->isDisplayedFor($view);
            })
        );
    }

    public function indexFields()
    {
        return $this->fieldsForView(ResourceView::INDEX);
    }

    public function detailFields()
    {
        return $this->fieldsForView(ResourceView::DETAIL);
    }

    public function createFields()
    {
        return $this->fieldsForView(ResourceView::CREATE);
    }

    public function editFields()
    {
        return $this->fieldsForView(ResourceView::EDIT);
    }

    public function merge($fields)
    {
        return array_merge($this->fieldsForView($this->view), $fields);
    }
}
