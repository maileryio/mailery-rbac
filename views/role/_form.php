<?php

use Yiisoft\Form\Widget\Form;
use Mailery\Rbac\Widget\TypeheadInput;
use Yiisoft\Form\Helper\HtmlForm;

/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\View\WebView $this */
/** @var Yiisoft\Form\FormModelInterface $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>
<div class="row">
    <div class="col-12 col-xl-4">
        <?= Form::widget()
                ->csrf($csrf)
                ->id('role-form')
                ->begin(); ?>

        <?= $field->text($form, 'name')
                ->autofocus(); ?>

        <?= $field->text($form, 'ruleName')
                ->template(strtr(
                    "{label}\n{input}\n{hint}\n{error}",
                    [
                        '{input}' => TypeheadInput::widget()
                            ->url($urlGenerator->generate('/rbac/rule/suggestions'))
                            ->name(HtmlForm::getInputName($form, 'ruleName'))
                            ->value(HtmlForm::getAttributeValue($form, 'ruleName') ?: ''),
                    ]
                )); ?>

        <?= $field->textArea($form, 'description', ['rows()' => [5]]); ?>

        <?= $field->submitButton()
                ->class('btn btn-primary float-right mt-2')
                ->value('Save'); ?>

        <?= Form::end(); ?>
    </div>
</div>