<?php declare(strict_types=1);

use Mailery\Web\Widget\FlashMessage;
use Yiisoft\Yii\Widgets\ContentDecorator;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Role $role */
/** @var Mailery\Rbac\Form\RoleForm $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle('Edit Role #' . $role->getName());

?>

<?= ContentDecorator::widget()
    ->viewFile('@vendor/maileryio/mailery-rbac/views/role/_layout.php')
    ->parameters(compact('role', 'csrf'))
    ->begin(); ?>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <?= FlashMessage::widget(); ?>
    </div>
</div>
<div class="mb-2"></div>

<div class="row">
    <div class="col-12">
        <?= $this->render('_form', compact('csrf', 'form')) ?>
    </div>
</div>

<?= ContentDecorator::end() ?>
