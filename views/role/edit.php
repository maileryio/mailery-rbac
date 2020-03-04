<?php

use Mailery\Widget\Form\FormRenderer;
use Mailery\Icon\Icon;

/** @var Mailery\Web\View\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Role $role */
/** @var Mailery\Rbac\Form\RoleForm $roleForm */
/** @var bool $submitted */

$this->setTitle('Edit Role #' . $role->getName());

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Edit Role #<?= $role->getName() ?></h1>
            <div class="btn-toolbar float-right">
                <a class="btn btn-sm btn-info mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/role/view', ['name' => $role->getName()]) ?>">
                    <?= Icon::widget()->name('eye')->options(['class' => 'mr-1']); ?>
                    View
                </a>
                <a class="btn btn-sm btn-outline-secondary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/role/index') ?>">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 grid-margin">
        <?= (new FormRenderer())($roleForm, $submitted) ?>
    </div>
</div>