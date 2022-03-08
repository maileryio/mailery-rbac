<?php

use Yiisoft\Html\Html;
use Yiisoft\Form\Widget\Form;

/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\View\WebView $this */
/** @var Mailery\Rbac\Form\RuleForm $form */
/** @var string $csrf */

?>
<div class="row">
    <div class="col-12 col-xl-4">
        <?= Form::widget()
            ->options(
                [
                    'id' => 'form-rule',
                    'csrf' => $csrf,
                    'enctype' => 'multipart/form-data',
                ]
            )
            ->begin(); ?>

        <?= $field->config($form, 'name'); ?>

        <?= $field->config($form, 'className'); ?>

        <?= Html::submitButton(
            'Save',
            [
                'class' => 'btn btn-primary float-right mt-2',
            ]
        ); ?>

        <?= Form::end(); ?>
    </div>
</div>