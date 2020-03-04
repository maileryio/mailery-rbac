<?php

use Mailery\Dataview\DetailView;
use Mailery\Web\Widget\Icon;
use Yiisoft\Html\Html;

/** @var Mailery\Web\View\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Permission $permission */
/** @var bool $submitted */

$this->setTitle($permission->getName());

$this->addBreadcrumb([
    'label' => 'Permissions',
    'url' => $urlGenerator->generate('/rbac/permission/index'),
    'class' => 'text-muted',
]);
$this->addBreadcrumb($permission->getName());

?><div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="float-right">
                    <a class="btn btn-info mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/permission/edit', ['name' => $permission->getName()]) ?>">
                        <?= Icon::widget()->options(['class' => 'mr-1'])->name('edit'); ?>
                        Update
                    </a>
                    <?= Html::a(
                        Icon::widget()->name('delete') . ' Delete',
                        $urlGenerator->generate('/rbac/permission/delete', ['name' => $permission->getName()]),
                        [
                            'is' => 'link',
                            'class' => 'btn btn-danger mx-sm-1 mb-2',
                            'data' => [
                                'method' => 'delete',
                                'confirm' => 'Are you sure?',
                            ],
                        ]
                    ); ?>
                    <a class="btn btn-primary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/permission/create') ?>">
                        <?= Icon::widget()->options(['class' => 'mr-1'])->name('add'); ?>
                        New Permission
                    </a>
                </div>
            </div>
        </div>
        <div class="mb-2"></div>
        <div class="row">
            <div class="col-12 grid-margin">
                <?= DetailView::widget()
                    ->data($permission)
                    ->options([
                        'class' => 'table detail-view',
                    ])
                    ->emptyText('(not set)')
                    ->emptyTextOptions([
                        'class' => 'text-muted',
                    ])
                    ->attributes([
                        [
                            'label' => 'Name',
                            'value' => function ($data, $index) {
                                return $data->getName();
                            },
                        ],
                        [
                            'label' => 'Rule',
                            'value' => function ($data, $index) {
                                return $data->getRuleName();
                            },
                        ],
                        [
                            'label' => 'Description',
                            'value' => function ($data, $index) {
                                return $data->getDescription();
                            },
                        ],
                    ]);
                ?>
            </div>
        </div>
    </div>
</div>
