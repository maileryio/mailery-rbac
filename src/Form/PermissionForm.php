<?php

namespace Mailery\Rbac\Form;

use Yiisoft\Rbac\Permission;
use Yiisoft\Rbac\ManagerInterface as RbacManager;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use FormManager\Form;
use FormManager\Factory as F;

class PermissionForm extends Form
{

    /**
     * @var Permission
     */
    private ?Permission $permission;

    /**
     * @var RbacManager
     */
    private RbacManager $rbacManager;

    /**
     * @param RbacManager $rbacManager
     */
    public function __construct(RbacManager $rbacManager)
    {
        $this->rbacManager = $rbacManager;
        parent::__construct($this->inputs());
    }

    /**
     * @param Permission $permission
     * @return self
     */
    public function withPermission(Permission $permission): self
    {
        $this->permission = $permission;
        $this->offsetSet('', F::submit('Update'));

        $this['name']->setValue($permission->getName());
        $this['ruleName']->setValue($permission->getRuleName());
        $this['description']->setValue($permission->getDescription());

        return $this;
    }

    /**
     * @return Permission
     */
    public function save(): Permission
    {
        $name = $this['name']->getValue();
        $ruleName = $this['ruleName']->getValue();
        $description = $this['description']->getValue();
        $timestamp = time();

        if ($this->permission === null) {
            $permission = (new Permission($name))
                ->withCreatedAt($timestamp);
        } else {
            $permission = clone $this->permission;
        }

        $permission = $permission
            ->withName($name)
            ->withRuleName(!empty($ruleName) ? $ruleName : null)
            ->withDescription($description)
            ->withUpdatedAt($timestamp);

        if ($this->permission === null) {
            $this->rbacManager->add($permission);
        } else {
            $this->rbacManager->update($this->permission->getName(), $permission);
        }

        return $permission;
    }

    /**
     * @return array
     */
    private function inputs(): array
    {
        $uniqueNameConstraint = new Constraints\Callback([
            'callback' => function ($value, ExecutionContextInterface $context) {
                $permissions = $this->rbacManager->getPermissions();
                $permissionName = $this->permission !== null ? $this->permission->getName() : null;

                if ($value !== $permissionName && isset($permissions[$value])) {
                    $context->buildViolation('This permission name already exists.')
                        ->atPath('name')
                        ->addViolation();
                }
            }
        ]);

        $existRuleConstraint = new Constraints\Callback([
            'callback' => function ($value, ExecutionContextInterface $context) {
                if (empty($value)) {
                    return;
                }

                $roles = $this->rbacManager->getRules();
                if (!isset($roles[$value])) {
                    $context->buildViolation('This rule name must exist.')
                        ->atPath('ruleName')
                        ->addViolation();
                }
            }
        ]);

        return [
            'name' => F::text('Name')
                ->addConstraint(new Constraints\NotBlank())
                ->addConstraint(new Constraints\Regex([
                    'pattern' => '/^[a-zA-Z]+$/i',
                ]))
                ->addConstraint($uniqueNameConstraint),
            'ruleName' => F::text('Rule Name')
                ->addConstraint($existRuleConstraint),
            'description' => F::textarea('Description', ['rows' => 5]),
            '' => F::submit($this->role === null ? 'Create' : 'Update'),
        ];
    }

}
