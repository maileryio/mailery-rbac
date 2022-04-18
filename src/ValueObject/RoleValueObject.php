<?php

namespace Mailery\Rbac\ValueObject;

use Mailery\Rbac\Form\RoleForm;

class RoleValueObject
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
     * @param RoleForm $form
     * @return self
     */
    public static function fromForm(RoleForm $form): self
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
