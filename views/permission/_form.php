<?php

use Yiisoft\Html\Tag\Form;
use Mailery\Widget\Typeahead\Typeahead;
use Yiisoft\Form\Field;
use Mailery\Web\Vue\Directive;

/** @var Yiisoft\View\WebView $this */
/** @var Yiisoft\Form\FormModelInterface $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>
<div class="row">
    <div class="col-12">
        <?= Form::tag()
            ->csrf($csrf)
            ->id('permission-form')
            ->post()
            ->open(); ?>

        <?= Field::text($form, 'name')->autofocus(); ?>

        <?= Field::text(
            $form,
            'ruleName',
            [
                'class' => Typeahead::class,
                'url()' => [$url->generate('/rbac/rule/suggestions')],
            ]
        ); ?>

        <?= Directive::pre(Field::textarea($form, 'description', ['rows()' => [5]])); ?>

        <?= Field::submitButton()
            ->content('Save'); ?>

        <?= Form::tag()->close(); ?>
    </div>
</div>