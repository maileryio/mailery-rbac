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

use Yiisoft\Rbac\Role;
use Yiisoft\Rbac\ItemsStorageInterface;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\MatchRegularExpression;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Result;
use Yiisoft\Form\FormModel;

class RoleForm extends FormModel
{

    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var string|null
     */
    private ?string $ruleName = null;

    /**
     * @var string|null
     */
    private ?string $description = null;

    /**
     * @var Role|null
     */
    private ?Role $role = null;

    /**
     * @param ItemsStorageInterface $itemsStorage
     */
    public function __construct(
        private ItemsStorageInterface $itemsStorage
    ) {
        parent::__construct();
    }

    /**
     * @param Role $role
     * @return self
     */
    public function withRole(Role $role): self
    {
        $new = clone $this;
        $new->role = $role;
        $new->name = $role->getName();
        $new->ruleName = $role->getRuleName();
        $new->description = $role->getDescription();

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
    public function getRuleName(): ?string
    {
        return $this->ruleName;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'name' => 'Name',
            'ruleName' => 'Rule name',
            'description' => 'Description',
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

                    if ($this->role === null && $this->itemsStorage->getRole($value) !== null) {
                        $result->addError('This role name already exists.');
                    }
                    if ($this->itemsStorage->getPermission($value) !== null) {
                        $result->addError('This name conflicted with permission.');
                    }

                    return $result;
                }),
            ],
            'ruleName' => [
                Required::rule(),
                HasLength::rule()->min(3)->max(255),
                Callback::rule(function ($value) {
                    $result = new Result();

                    if ($this->itemsStorage->getRuleByName($value) === null) {
                        $result->addError('This rule name must be exist.');
                    }

                    return $result;
                }),
            ],
        ];
    }

}
