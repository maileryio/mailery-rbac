<?php

use Mailery\Web\Backend\Widget\FormRenderer;

/** @var Mailery\Web\View\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var FormManager\Form $permissionForm */
/** @var bool $submitted */

$this->setTitle('New Permission');

$this->addBreadcrumb([
    'label' => 'Permissions',
    'url' => $urlGenerator->generate('/rbac/permission/index'),
    'class' => 'text-muted',
]);
$this->addBreadcrumb('New Permission');

?><div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 grid-margin">
                <?= (new FormRenderer())($permissionForm, $submitted) ?>
            </div>
        </div>
    </div>
</div>
