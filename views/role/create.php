<?php declare(strict_types=1);

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Mailery\Rbac\Form\RoleForm $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle('New role');

?><div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <h4 class="mb-0">New role</h4>
                    </div>
                    <div class="col-auto">
                        <div class="btn-toolbar float-right">
                            <a class="btn btn-sm btn-outline-secondary mx-sm-1 mb-2" href="<?= $url->generate('/rbac/role/index'); ?>">
                                Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <?= $this->render('_form', compact('csrf', 'form')) ?>
            </div>
        </div>
    </div>
</div>