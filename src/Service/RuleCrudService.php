<?php

namespace Mailery\Rbac\Service;

use Mailery\Rbac\ValueObject\RuleValueObject;
use Yiisoft\Rbac\Rule;
use Yiisoft\Rbac\Manager as RbacManager;

class RuleCrudService
{
    /**
     * @param RbacManager $rbacManager
     */
    public function __construct(
        private RbacManager $rbacManager
    ) {}

    /**
     * @param RuleValueObject $valueObject
     * @return Rule
     */
    public function create(RuleValueObject $valueObject): Rule
    {
        $className = $valueObject->getClassName();
        $rule = (new $className($valueObject->getName()));

        $this->rbacManager->addRule($rule);

        return $rule;
    }

    /**
     * @param Rule $rule
     * @param RuleValueObject $valueObject
     * @return Template
     */
    public function update(Rule $rule, RuleValueObject $valueObject): Rule
    {
        $name = $rule->getName();

        $rule = $rule
            ->withName($valueObject->getName());

        $this->rbacManager->updateRule($name, $rule);

        return $rule;
    }

    /**
     * @param Rule $rule
     * @return bool
     */
    public function delete(Rule $rule): bool
    {
        $this->rbacManager->removeRule($rule);

        return true;
    }
}
