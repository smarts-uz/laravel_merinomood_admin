<?php

namespace Arniro\Admin\Http\Resources;

use Arniro\Admin\Fields\Relationship;
use Arniro\Admin\Http\Requests\AdminRequest;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

abstract class Resource implements Responsable
{
    use Authorization, PerformsValidation, Fields, ViewDetection;

    public static $model;
    public static $search = ['id'];
    public $resource;
    public static $icon = 'list';
    public static $orderable = false;
    public static $title = 'id';
    public static $paginate = 25;

    protected $via = null;
    protected $viaField = null;

    public function __construct(?Model $resource = null, $via = null, $viaField = null)
    {
        $this->resource = $resource ?: $this->newModel();
        $this->via = $via;
        $this->viaField = $viaField;

        $this->defineGate();

        app()->call([$this, 'setUp']);
    }

    public static function collection($collection, $via = null, $viaField = null)
    {
        $rawCollection = $collection instanceof LengthAwarePaginator ? $collection->getCollection() : $collection;

        $resources = $rawCollection->map(function ($model) use ($via, $viaField) {
            return (new static($model, $via, $viaField))->forView(ResourceView::INDEX);
        });

        return array_merge([
            'resources' => $resources->map->resourceData(),
            'can' => static::abilities($resources, $viaField)
        ], static::meta($collection));
    }

    protected function resourceData()
    {
        $this->setCurrentView();

        return [
            'id' => $this->resource->id,
            'data' => $this->resource->toArray(),
            'fields' => $this->getFields(),
            'can' => $this->detailAbilities(),
            'view' => $this->view
        ];
    }

    protected static function meta($collection = null)
    {
        return array_merge([
            'name' => static::resourceName(),
            'route' => static::route(),
            'label' => static::label(),
            'icon' => static::$icon,
            'searchable' => !!static::$search,
            'orderable' => !!static::$orderable,
        ], static::paginatingMeta($collection));
    }

    protected static function paginatingMeta($collection = null)
    {
        return $collection instanceof LengthAwarePaginator ? [
            'pagination' => [
                'total' => $collection->total(),
                'current_page' => $collection->currentPage(),
                'last_page' => $collection->lastPage(),
                'from' => $collection->firstItem(),
                'to' => $collection->lastItem()
            ]
        ] : [];
    }

    public function toResponse($request)
    {
        return array_merge([
            'resource' => $this->resourceData(),
            'relationships' => $this->relationships()
        ], static::meta());
    }

    public static function fetch($query = null, $via = null, $viaField = null)
    {
        /**
         * AdminRequest
         */
        $request = resolve(AdminRequest::class);

        $pageName = 'page';

        if ($request->isViaResource() && $viaRelationship = $request->viaRelationship()) {
            $pageName = $request->input('viaRelationship') . '_page';

            $query = $query ?: $viaRelationship;
        }

        $query = $query ?: static::newModel()->query();

        if ($via && $viaField && Schema::hasColumn($query->getTable(), $query->getModel()->getKeyName())) {
            $query->orderBy($query->getTable() . '.id', 'desc');
        } else {
            static::orderBy($query);
        }

        static::indexQuery($query, $request);

        $results = static::$paginate ?
            $query->paginate(static::$paginate, ['*'], $pageName) :
            $query->get();

        return static::collection($results, $via, $viaField);
    }

    protected static function orderBy($query)
    {
        if ($orderColumn = request(static::resourceName() . '_order')) {
            return $query->orderBy(
                $orderColumn,
                request(static::resourceName() . '_direction')
            );
        }

        if (static::$orderable) {
            return $query->orderBy('index', 'asc');
        }

        return Schema::hasColumn($query->getModel()->getTable(), $query->getModel()->getCreatedAtColumn())
            ? $query->latest()
            : $query;
    }

    /**
     * @param Builder|Relation $query
     * @param AdminRequest $request
     */
    protected static function indexQuery($query, AdminRequest $request)
    {
        //
    }

    /**
     * @param Request $request
     * @return Model
     */
    public function store(Request $request)
    {
        if ($this->shouldValidate) {
            $this->validateForCreate($request);
        }

        $model = $this->resource();

        foreach ($this->storingFields() as $field) {
            app()->call([$field, 'store'], ['request' => $request, 'model' => $model]);
        }

        $model->save();

        foreach ($this->fields() as $field) {
            app()->call([$field, 'storeSaved'], ['request' => $request, 'model' => $model]);
        }

        return $model;
    }

    /**
     * @param Request $request
     * @return Model
     */
    public function update(Request $request)
    {
        $model = $this->resource();

        if ($this->shouldValidate) {
            $this->validateForUpdate($request, $model);
        }

        foreach ($this->updatingFields() as $field) {
            app()->call([$field, 'update'], ['request' => $request, 'model' => $model]);
        }

        return tap($model)->save();
    }

    public function destroy()
    {
        foreach ($this->fields() as $field) {
            app()->call([$field, 'destroy'], ['model' => $this->resource()]);
        }

        $this->resource()->delete();
    }

    public function __get($name)
    {
        return $this->resource->$name;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        }

        return $this->resource->$name(...$arguments);
    }

    public static function model()
    {
        return static::$model;
    }

    public static function newModel()
    {
        $model = static::model();

        return new $model;
    }

    /**
     * @return Model|null
     */
    public function resource()
    {
        return $this->resource;
    }

    public static function resourceName()
    {
        return Str::plural(Str::kebab(class_basename(static::class)));
    }

    public static function label()
    {
        $plural = Str::plural(class_basename(static::class));

        return ucwords(str_replace(
            '_',
            ' ',
            Str::snake($plural)
        ));
    }

    public static function title()
    {
        return static::$title;
    }

    public function getSearchColumns()
    {
        return static::$search;
    }

    protected static function route()
    {
        return static::resourceName();
    }

    public function resolveField($attribute, $resource = null)
    {
        return collect($this->fields())->first(function ($field) use ($attribute, $resource) {
            if ($resource) {
                return $field->attribute === $attribute && $field->resource === $resource;
            }

            return $field->attribute === $attribute;
        });
    }

    public function setVia(Resource $resource, Relationship $field)
    {
        $this->via = $resource;
        $this->viaField = $field;

        return $this;
    }

    protected function setUp()
    {
        //
    }

    /**
     * @return ResourceSuggestions
     */
    public function suggestions()
    {
        return new ResourceSuggestions($this);
    }

    /**
     * Compare current resource with the another one.
     *
     * @param Resource $resource
     * @return bool
     */
    public function is(Resource $resource)
    {
        return class_basename($this) === class_basename($resource);
    }
}
