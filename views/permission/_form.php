<?php

use Yiisoft\Html\Html;
use Yiisoft\Form\Widget\Form;
use Mailery\Rbac\Widget\TypeheadInput;
use Yiisoft\Form\Helper\HtmlForm;

/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\View\WebView $this */
/** @var Mailery\Rbac\Form\PermissionForm $form */
/** @var string $csrf */

?>
<div class="row">
    <div class="col-12 col-xl-4">
        <?= Form::widget()
            ->options(
                [
                    'id' => 'form-permission',
                    'csrf' => $csrf,
                    'enctype' => 'multipart/form-data',
                ]
            )
            ->begin(); ?>

        <?= $field->config($form, 'name'); ?>

        <?= $field->config($form, 'ruleName')
                ->template(strtr(
                    "{label}\n{input}\n{hint}\n{error}",
                    [
                        '{input}' => TypeheadInput::widget()
                            ->url($urlGenerator->generate('/rbac/rule/suggestions'))
                            ->name(HtmlForm::getInputName($form, 'ruleName'))
                            ->value(HtmlForm::getAttributeValue($form, 'ruleName') ?: ''),
                    ]
                )); ?>

        <?= $field->config($form, 'description')
                ->textArea([
                    'rows' => 5,
                ]);
        ?>

        <?= Html::submitButton(
            'Save',
            [
                'class' => 'btn btn-primary float-right mt-2',
            ]
        ); ?>

        <?= Form::end(); ?>
    </div>
</div>