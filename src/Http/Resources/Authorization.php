<?php

namespace Arniro\Admin\Http\Resources;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

trait Authorization
{
    use AuthorizesRequests;

    protected static $can = [];

    /**
     * @var Resource
     */
    protected $authorizableResource;

    public static function abilities(Collection $collection, $pivot = true)
    {
        static::buildCans($pivot);

        if (! static::authorizable()) {
            return static::$can;
        }

        $can = collect(static::$can)->except('create')
            ->mapWithKeys(function ($value, $ability) use ($collection) {
                return [$ability => !!$collection->first->allows($ability)];
            })->toArray();

        if (!$pivot) {
            $can['create'] = static::authorizedToCreate();
        } elseif (!$collection->first()) {
            $can['attach'] = true;
        }

        return $can;
    }

    public function detailAbilities()
    {
        if (! static::authorizable() || in_array($this->view, [ResourceView::CREATE, ResourceView::INDEX])) {
            return static::$can;
        }

        return collect(static::$can)->mapWithKeys(function ($value, $ability) {
            if ($ability === 'create') {
                return [$ability => $value];
            }

            return [$ability => $this->allows($ability)];
        })->toArray();
    }

    /**
     * Determine whether a gate is defined or a policy is registered for the resource.
     *
     * @param Resource|null $resource
     * @return bool
     */
    public static function authorizable(?Resource $resource = null)
    {
        $resource = $resource ?: new static;

        return Gate::has($resource::gateName()) || !! Gate::getPolicyFor($resource::model());
    }

    public static function authorizedToCreate()
    {
        if (! static::authorizable()) {
            return true;
        }

        $policy = Gate::getPolicyFor(static::model());

        $ability = method_exists($policy, 'create') ? 'create' : static::gateName();

        return Gate::allows($ability, static::newModel());
    }

    public function authorize($ability = null, $arguments = [])
    {
        $this->setAuthorizableResource($ability);

        if (! static::authorizable($this->authorizableResource())) {
            return $this;
        }

        Gate::authorize(...$this->prepareForAuthorization($ability, $arguments));

        return $this;
    }

    public function allows($ability = null, $arguments = [])
    {
        $this->setAuthorizableResource($ability);

        if (! static::authorizable($this->authorizableResource())) {
            return true;
        }

        return Gate::allows(...$this->prepareForAuthorization($ability, $arguments));
    }

    protected function prepareForAuthorization($ability, $arguments)
    {
        [$ability, $arguments] = $this->parseAbilityAndArguments($ability, $arguments);

        if (! $arguments) {
            $arguments = $this->authorizableResource()->resource()->exists ?
                [$this->authorizableResource()->resource()] :
                [$this->authorizableResource()::newModel()];

            if ($this->authorizableResource) {
                $arguments[] = $this->resource();
            }
        }

        $policy = Gate::getPolicyFor($this->authorizableResource()::model());

        if (! method_exists($policy, $ability)) {
            $ability = $this->authorizableResource()::gateName();
        }

        return [$ability, $arguments];
    }

    protected static function buildCans($pivot)
    {
        if ($pivot) {
            static::$can = [
                'view' => true,
                'attach' => true,
                'detach' => true,
            ];
        } else {
            static::$can = [
                'view' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
            ];
        }
    }

    protected function parseAbilityAndArguments($ability, $arguments)
    {
        if ($this->viaField) {
            $ability .= class_basename($this->viaField->resource);
        }

        if (is_string($ability) && strpos($ability, '\\') === false) {
            return [$ability, $arguments];
        }

        $method = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4)[3]['function'];

        return [$this->normalizeGuessedAbilityName($method), $ability];
    }

    protected function authorizableResource()
    {
        return $this->authorizableResource ?: $this;
    }

    protected function setAuthorizableResource($ability)
    {
        if ($this->via && $ability !== 'view') {
            $this->authorizableResource = $this->via;
        }

        return $this;
    }

    protected function defineGate()
    {
        if (is_null($gateCallback = $this->gate())) {
            return;
        }

        Gate::define(static::gateName(), $gateCallback);
    }

    protected static function gateName()
    {
        return 'manage-' . static::route();
    }

    protected function gate()
    {
        //
    }
}
