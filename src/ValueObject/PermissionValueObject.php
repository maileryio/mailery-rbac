<?php

namespace Mailery\Rbac\ValueObject;

use Mailery\Rbac\Form\PermissionForm;

class PermissionValueObject
{

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $ruleName;

    /**
     * @var string
     */
    private string $description;

    /**
     * @param PermissionForm $form
     * @return self
     */
    public static function fromForm(PermissionForm $form): self
    {
        $new = new self();
        $new->name = $form->getName();
        $new->ruleName = $form->getRuleName();
        $new->description = $form->getDescription();

        return $new;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRuleName(): string
    {
        return $this->ruleName;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

}
