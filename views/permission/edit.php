<?php declare(strict_types=1);

use Mailery\Icon\Icon;

/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Permission $permission */
/** @var Mailery\Rbac\Form\PermissionForm $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle('Edit Permission #' . $permission->getName());

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
            <h1 class="h3">Edit permission #<?= $permission->getName(); ?></h1>
            <div class="btn-toolbar float-right">
                <a class="btn btn-sm btn-info mx-sm-1 mb-2" href="<?= $url->generate('/rbac/permission/view', ['name' => $permission->getName()]); ?>">
                    <?= Icon::widget()->name('eye')->options(['class' => 'mr-1']); ?>
                    View
                </a>
                <a class="btn btn-sm btn-outline-secondary mx-sm-1 mb-2" href="<?= $url->generate('/rbac/permission/index'); ?>">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->render('_form', compact('csrf', 'field', 'form')) ?>
