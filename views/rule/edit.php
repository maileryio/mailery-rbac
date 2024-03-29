<?php declare(strict_types=1);

use Mailery\Icon\Icon;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Rule $rule */
/** @var Mailery\Rbac\Form\RuleForm $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle('Edit Rule #' . $rule->getName());

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
            <h1 class="h3">Edit rule #<?= $rule->getName(); ?></h1>
            <div class="btn-toolbar float-right">
                <a class="btn btn-sm btn-info mx-sm-1 mb-2" href="<?= $url->generate('/rbac/rule/view', ['name' => $rule->getName()]); ?>">
                    <?= Icon::widget()->name('eye')->options(['class' => 'mr-1']); ?>
                    View
                </a>
                <a class="btn btn-sm btn-outline-secondary mx-sm-1 mb-2" href="<?= $url->generate('/rbac/rule/index'); ?>">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->render('_form', compact('csrf', 'form')) ?>
