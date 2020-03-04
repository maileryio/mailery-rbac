<?php

use Mailery\Web\Backend\Widget\FormRenderer;

/** @var Mailery\Web\View\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var FormManager\Form $roleForm */
/** @var bool $submitted */

$this->setTitle('New Role');

$this->addBreadcrumb([
    'label' => 'Roles',
    'url' => $urlGenerator->generate('/rbac/role/index'),
    'class' => 'text-muted',
]);
$this->addBreadcrumb('New Role');

?><div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 grid-margin">
                <?= (new FormRenderer())($roleForm, $submitted) ?>
            </div>
        </div>
    </div>
</div>
