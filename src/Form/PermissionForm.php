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

use Yiisoft\Rbac\Permission;
use Yiisoft\Rbac\ItemsStorageInterface;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\MatchRegularExpression;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Result;
use Yiisoft\Form\FormModel;

class PermissionForm extends FormModel
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
     * @var Permission|null
     */
    private ?Permission $permission = null;

    /**
     * @param ItemsStorageInterface $itemsStorage
     */
    public function __construct(
        private ItemsStorageInterface $itemsStorage
    ) {
        parent::__construct();
    }

    /**
     * @param Permission $permission
     * @return self
     */
    public function withPermission(Permission $permission): self
    {
        $new = clone $this;
        $new->permission = $permission;
        $new->name = $permission->getName();
        $new->ruleName = $permission->getRuleName();
        $new->description = $permission->getDescription();

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

                    if ($this->permission === null && $this->itemsStorage->getPermission($value) !== null) {
                        $result->addError('This permission name already exists.');
                    }
                    if ($this->itemsStorage->getRole($value) !== null) {
                        $result->addError('This name conflicted with role.');
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
