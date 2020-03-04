<?php

use Mailery\Widget\Dataview\DetailView;
use Mailery\Icon\Icon;
use Mailery\Widget\Link\Link;
use Yiisoft\Html\Html;

/** @var Mailery\Web\View\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Role $role */
/** @var bool $submitted */

$this->setTitle($role->getName());

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Role #<?= $role->getName() ?></h1>
            <div class="btn-toolbar float-right">
                <?= Link::widget()
                    ->label(Icon::widget()->name('delete')->options(['class' => 'mr-1']) . ' Delete')
                    ->method('delete')
                    ->href($urlGenerator->generate('/rbac/role/delete', ['name' => $role->getName()]))
                    ->confirm('Are you sure?')
                    ->options([
                        'class' => 'btn btn-sm btn-danger mx-sm-1 mb-2',
                    ]);
                ?>
                <a class="btn btn-sm btn-secondary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/role/edit', ['name' => $role->getName()]) ?>">
                    <?= Icon::widget()->name('pencil')->options(['class' => 'mr-1']); ?>
                    Update
                </a>
                <div class="btn-toolbar float-right">
                    <a class="btn btn-sm btn-outline-secondary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/role/index') ?>">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mb-2"></div>
<div class="row">
    <div class="col-12 grid-margin">
        <?= DetailView::widget()
            ->data($role)
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
<div class="mb-2"></div>
<div class="row">
    <div class="col-12 grid-margin">
        <?php
            $items = [
                [
                    'id' => 1,
                    'label' => 'JavaScript',
                    'children' => [
                        [
                            'id' => 2,
                            'label' => 'jQuery',
                            'children' => [],
                        ],
                        [
                            'id' => 3,
                            'label' => 'AngularJS',
                            'children' => [
                                [
                                    'id' => 5,
                                    'label' => 'Java',
                                    'checked' => true,
                                    'children' => [],
                                ],
                                [
                                    'id' => 6,
                                    'label' => 'Python',
                                    'children' => [],
                                ]
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 7,
                    'label' => 'JavaScript',
                    'children' => [
                        [
                            'id' => 8,
                            'label' => 'jQuery',
                            'children' => [],
                        ],
                        [
                            'id' => 9,
                            'label' => 'AngularJS',
                            'children' => [
                                [
                                    'id' => 10,
                                    'label' => 'Java',
                                    'children' => [],
                                ],
                                [
                                    'id' => 11,
                                    'label' => 'xxx',
                                    'children' => [],
                                ]
                            ],
                        ],
                    ],
                ],
            ];
        ?>
        <dual-listbox items="<?= Html::encode(json_encode($items)); ?>"></dual-listbox>
    </div>
</div>
