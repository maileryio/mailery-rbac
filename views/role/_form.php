<?php

use Yiisoft\Form\Widget\Form;
use Mailery\Widget\Typeahead\Typeahead;

/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\View\WebView $this */
/** @var Yiisoft\Form\FormModelInterface $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>
<div class="row">
    <div class="col-12">
        <?= Form::widget()
                ->csrf($csrf)
                ->id('role-form')
                ->begin(); ?>

        <?= $field->text($form, 'name')->autofocus(); ?>

        <?= $field->text(
                $form,
                'ruleName',
                [
                    'class' => Typeahead::class,
                    'url()' => [$url->generate('/rbac/rule/suggestions')],
                ]
            ); ?>

        <?= $field->textArea($form, 'description', ['rows()' => [5]]); ?>

        <?= $field->submitButton()
                ->class('btn btn-primary float-right mt-2')
                ->value('Save'); ?>

        <?= Form::end(); ?>
    </div>
</div>