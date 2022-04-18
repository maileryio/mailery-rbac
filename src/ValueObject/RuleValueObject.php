<?php

namespace Mailery\Rbac\ValueObject;

use Mailery\Rbac\Form\RuleForm;

class RuleValueObject
{

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $className;

    /**
     * @param RuleForm $form
     * @return self
     */
    public static function fromForm(RuleForm $form): self
    {
        $new = new self();
        $new->name = $form->getName();
        $new->className = $form->getClassName();

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
    public function getClassName(): string
    {
        return $this->className;
    }

}
