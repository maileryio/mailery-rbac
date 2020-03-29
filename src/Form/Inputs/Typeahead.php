<?php

namespace Mailery\Rbac\Form\Inputs;

use FormManager\Inputs\Input;

class Typeahead extends Input
{

    /**
     * @var array
     */
    protected $validators = [
        'required' => 'required',
        'length' => ['minlength', 'maxlength'],
        'pattern' => 'pattern'
    ];

    /**
     * @param string $label
     * @param iterable $attributes
     */
    public function __construct(string $label = null, iterable $attributes = [])
    {
        parent::__construct('ui-typeahead', $attributes);
        $this->setAttribute('type', 'text');

        if (isset($label)) {
            $this->setLabel($label);
        }
    }

}
