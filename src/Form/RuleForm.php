<?php

declare(strict_types=1);

/**
 * Rbac module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-rbac
 * @package   Mailery\Rbac
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Rbac\Form;

use FormManager\Factory as F;
use FormManager\Form;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Yiisoft\Rbac\ManagerInterface as RbacManager;
use Yiisoft\Rbac\Rule;

class RuleForm extends Form
{
    /**
     * @var Rule|null
     */
    private ?Rule $rule;

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
     * @param Rule $rule
     * @return self
     */
    public function withRule(Rule $rule): self
    {
        $this->rule = $rule;
        $this->offsetSet('', F::submit('Update'));

        $this['name']->setValue($rule->getName());
        $this['className']->setValue(get_class($rule));

        return $this;
    }

    /**
     * @return Rule|null
     */
    public function save(): ?Rule
    {
        if (!$this->isValid()) {
            return null;
        }

        $name = $this['name']->getValue();
        $className = $this['className']->getValue();

        $rule = (new $className($name))
            ->withName($name);

        if ($this->rule === null) {
            $this->rbacManager->add($rule);
        } else {
            $this->rbacManager->update($this->rule->getName(), $rule);
        }

        return $rule;
    }

    /**
     * @return array
     */
    private function inputs(): array
    {
        $uniqueNameConstraint = new Constraints\Callback([
            'callback' => function ($value, ExecutionContextInterface $context) {
                $rules = $this->rbacManager->getRules();
                $ruleName = $this->rule !== null ? $this->rule->getName() : null;

                if ($value !== $ruleName && isset($rules[$value])) {
                    $context->buildViolation('This rule name already exists.')
                        ->atPath('name')
                        ->addViolation();
                }
            },
        ]);

        $classExistsConstraint = new Constraints\Callback([
            'callback' => function ($value, ExecutionContextInterface $context) {
                if (!class_exists($value)) {
                    $message = "Unknown class '{$value}'.";
                } else {
                    if (!is_subclass_of($value, Rule::class)) {
                        $message = "'{$value}' must extend from 'Yiisoft\\Rbac\\Rule' or its child class.";
                    }
                }

                if (!empty($message)) {
                    $context->buildViolation($message)
                        ->atPath('className')
                        ->addViolation();
                }
            },
        ]);

        return [
            'name' => F::text('Name')
                ->addConstraint(new Constraints\NotBlank())
                ->addConstraint(new Constraints\Regex([
                    'pattern' => '/^[a-zA-Z]+$/i',
                ]))
                ->addConstraint($uniqueNameConstraint),
            'className' => F::text('Class name')
                ->addConstraint(new Constraints\NotBlank())
                ->addConstraint(new Constraints\Regex([
                    'pattern' => '/^[a-zA-Z\\\]+$/i',
                ]))
                ->addConstraint($classExistsConstraint),
            '' => F::submit('Create'),
        ];
    }
}
