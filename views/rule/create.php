<?php declare(strict_types=1);

use Mailery\Widget\Form\FormRenderer;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var FormManager\Form $ruleForm */
/** @var string $csrf */
/** @var bool $submitted */

$this->setTitle('New Rule');

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
            <h1 class="h2">New rule</h1>
            <div class="btn-toolbar float-right">
                <a class="btn btn-sm btn-outline-secondary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/rule/index'); ?>">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="mb-2"></div>
<div class="row">
    <div class="col-6">
        <?= (new FormRenderer($ruleForm->withCsrf($csrf)))($submitted); ?>
    </div>
</div>