<?php

namespace Arniro\Admin\Fields;

use Arniro\Admin\Http\Resources\Resource;

class Password extends Field
{
    public $component = 'password-field';

    public $show = [
        'index' => false,
        'detail' => false,
        'edit' => true,
        'create' => true
    ];

    public $confirmation = false;

    /**
     * Normalize the value before persisting to the database.
     *
     * @param $value
     * @return mixed
     */
    protected function normalize($value)
    {
        return bcrypt($value);
    }

    public function getValue(Resource $resource)
    {
        return '';
    }

    /**
     * Add a confirmation next to the password field.
     *
     * @param null $label
     * @return $this
     */
    public function withConfirmation($label = null)
    {
        if (!$label) {
            $label = $this->label . ' (confirmation)';
        }

        $this->confirmation = $label;

        return $this;
    }
}
