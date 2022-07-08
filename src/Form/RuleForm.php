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

use Yiisoft\Rbac\Rule;
use Yiisoft\Rbac\ItemsStorageInterface;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\MatchRegularExpression;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Result;
use Yiisoft\Form\FormModel;

class RuleForm extends FormModel
{

    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var string|null
     */
    private ?string $className = null;

    /**
     * @var Rule|null
     */
    private ?Rule $rule = null;

    /**
     * @param ItemsStorageInterface $itemsStorage
     */
    public function __construct(
        private ItemsStorageInterface $itemsStorage
    ) {
        parent::__construct();
    }

    /**
     * @param Rule $rule
     * @return self
     */
    public function withRule(Rule $rule): self
    {
        $new = clone $this;
        $new->rule = $rule;
        $new->name = $rule->getName();
        $new->className = get_class($rule);

        return $new;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'name' => 'Name',
            'className' => 'Class name',
        ];
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'name' => [
                Required::rule(),
                HasLength::rule()->min(3)->max(255),
                MatchRegularExpression::rule('/^[a-zA-Z]+$/i'),
                Callback::rule(function ($value) {
                    $result = new Result();

                    if ($this->rule === null && $this->itemsStorage->getRuleByName($value) !== null) {
                        $result->addError('This rule name already exists.');
                    }

                    return $result;
                }),
            ],
            'className' => [
                Required::rule(),
                HasLength::rule()->min(3)->max(255),
                MatchRegularExpression::rule('/^[a-zA-Z\\\]+$/i'),
                Callback::rule(function ($value) {
                    $result = new Result();

                    if (!class_exists($value)) {
                        $message = "Unknown class '{$value}'.";
                    } else {
                        if (!is_subclass_of($value, Rule::class)) {
                            $message = "'{$value}' must extend from 'Yiisoft\\Rbac\\Rule' or its child class.";
                        }
                    }

                    if (!empty($message)) {
                        $result->addError($message);
                    }

                    return $result;
                }),
            ],
        ];
    }

}
