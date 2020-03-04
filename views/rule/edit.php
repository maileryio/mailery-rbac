<?php

use Mailery\Web\Backend\Widget\FormRenderer;

/** @var Mailery\Web\View\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Rule $rule */
/** @var Mailery\Rbac\Form\RuleForm $ruleForm */
/** @var bool $submitted */

$this->setTitle('Edit Rule #' . $rule->getName());

$this->addBreadcrumb([
    'label' => 'Rules',
    'url' => $urlGenerator->generate('/rbac/rule/index'),
    'class' => 'text-muted',
]);
$this->addBreadcrumb('Edit Rule');

?><div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 grid-margin">
                <?= (new FormRenderer())($ruleForm, $submitted) ?>
            </div>
        </div>
    </div>
</div>
