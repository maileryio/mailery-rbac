<?php

use Mailery\Widget\Form\FormRenderer;
use Mailery\Icon\Icon;

/** @var Mailery\Web\View\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Permission $permission */
/** @var Mailery\Rbac\Form\PermissionForm $permissionForm */
/** @var bool $submitted */

$this->setTitle('Edit Permission #' . $permission->getName());

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Edit Permission #<?= $permission->getName() ?></h1>
            <div class="btn-toolbar float-right">
                <a class="btn btn-sm btn-info mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/permission/view', ['name' => $permission->getName()]) ?>">
                    <?= Icon::widget()->name('eye')->options(['class' => 'mr-1']); ?>
                    View
                </a>
                <a class="btn btn-sm btn-outline-secondary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/permission/index') ?>">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 grid-margin">
        <?= (new FormRenderer())($permissionForm, $submitted) ?>
    </div>
</div>