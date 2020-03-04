<?php

namespace Mailery\Rbac\Form;

use Yiisoft\Rbac\Role;
use Yiisoft\Rbac\ManagerInterface as RbacManager;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use FormManager\Form;
use FormManager\Factory as F;

class RoleForm extends Form
{

    /**
     * @var Role
     */
    private ?Role $role;

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
     * @param Role $role
     * @return self
     */
    public function withRole(Role $role): self
    {
        $this->role = $role;
        $this->offsetSet('', F::submit('Update'));

        $this['name']->setValue($role->getName());
        $this['ruleName']->setValue($role->getRuleName());
        $this['description']->setValue($role->getDescription());

        return $this;
    }

    /**
     * @return Role
     */
    public function save(): Role
    {
        $name = $this['name']->getValue();
        $ruleName = $this['ruleName']->getValue();
        $description = $this['description']->getValue();
        $timestamp = time();

        if ($this->role === null) {
            $role = (new Role($name))
                ->withCreatedAt($timestamp);
        } else {
            $role = clone $this->role;
        }

        $role = $role
            ->withName($name)
            ->withRuleName(!empty($ruleName) ? $ruleName : null)
            ->withDescription($description)
            ->withUpdatedAt($timestamp);

        if ($this->role === null) {
            $this->rbacManager->add($role);
        } else {
            $this->rbacManager->update($this->role->getName(), $role);
        }

        return $role;
    }

    /**
     * @return array
     */
    private function inputs(): array
    {
        $uniqueNameConstraint = new Constraints\Callback([
            'callback' => function ($value, ExecutionContextInterface $context) {
                $roles = $this->rbacManager->getRoles();
                $roleName = $this->role !== null ? $this->role->getName() : null;

                if ($value !== $roleName && isset($roles[$value])) {
                    $context->buildViolation('This role name already exists.')
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
