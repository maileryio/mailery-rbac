<?php

use Mailery\Web\Backend\Widget\FormRenderer;

/** @var Mailery\Web\View\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Permission $permission */
/** @var Mailery\Rbac\Form\PermissionForm $permissionForm */
/** @var bool $submitted */

$this->setTitle('Edit Permission #' . $permission->getName());

$this->addBreadcrumb([
    'label' => 'Permissions',
    'url' => $urlGenerator->generate('/rbac/permission/index'),
    'class' => 'text-muted',
]);
$this->addBreadcrumb('Edit Permission');

?><div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 grid-margin">
                <?= (new FormRenderer())($permissionForm, $submitted) ?>
            </div>
        </div>
    </div>
</div>
