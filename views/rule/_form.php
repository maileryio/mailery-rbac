<?php

use Yiisoft\Html\Tag\Form;
use Yiisoft\Form\Field;

/** @var Yiisoft\View\WebView $this */
/** @var Yiisoft\Form\FormModelInterface $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>
<div class="row">
    <div class="col-12">
        <?= Form::tag()
                ->csrf($csrf)
                ->id('rule-form')
                ->post()
                ->open(); ?>

        <?= Field::text($form, 'name')->autofocus(); ?>

        <?= Field::text($form, 'className'); ?>

        <?= Field::submitButton()
                ->content('Save'); ?>

        <?= Form::tag()->close(); ?>
    </div>
</div>