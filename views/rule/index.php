<?php

use Mailery\Dataview\GridView;
use Mailery\Dataview\Columns\ActionColumn;
use Mailery\Dataview\Columns\DataColumn;
use Mailery\Dataview\Columns\SerialColumn;
use Mailery\Dataview\GridView\LinkPager;
use Mailery\Web\Widget\Icon;
use Yiisoft\Rbac\Rule;
use Yiisoft\Html\Html;

/** @var Mailery\Web\View\WebView $this */
/** @var Yiisoft\Aliases\Aliases $aliases */
/** @var Yiisoft\Router\UrlGeneratorInterface $urlGenerator */
/** @var Yiisoft\Data\Reader\DataReaderInterface $dataReader*/
/** @var Yiisoft\Data\Paginator\PaginatorInterface $paginator */

$this->setTitle('All Rules');
$this->addBreadcrumb('Rules');

?><div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="float-right">
                    <form class="form-inline float-left">
                        <div class="input-group mx-sm-1 mb-2">
                            <input type="text" class="form-control" placeholder="Search">
                            <div class="input-group-append">
                                <button class="btn btn-outline-light" type="button">
                                    <?= Icon::widget()->name('search')->options(['class' => 'text-secondary']); ?>
                                </button>
                            </div>
                        </div>
                    </form>
                    <a class="btn btn-secondary mb-2" href="#">
                        <?= Icon::widget()->name('settings'); ?>
                    </a>
                    <a class="btn btn-primary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/rule/create') ?>">
                        <?= Icon::widget()->options(['class' => 'mr-1'])->name('add'); ?>
                        New Rule
                    </a>
                </div>
            </div>
        </div>
        <div class="mb-2"></div>
        <div class="row">
            <div class="col-12">
                <?= GridView::widget()
                    ->paginator($paginator)
                    ->options([
                        'class' => 'table-responsive',
                    ])
                    ->tableOptions([
                        'class' => 'table table-hover',
                    ])
                    ->emptyText('No data')
                    ->emptyTextOptions([
                        'class' => 'text-center text-muted mt-4 mb-4',
                    ])
                    ->columns([
                        (new SerialColumn())
                            ->header('#')
                            ->paginator($paginator),
                        (new DataColumn())
                            ->header('Name')
                            ->content(function (Rule $data, int $index) {
                                return $data->getName();
                            }),
                        (new ActionColumn())
                            ->header('Actions')
                            ->view(function (Rule $data, int $index) use($urlGenerator) {
                                return Html::a(
                                    Icon::widget()->name('view'),
                                    $urlGenerator->generate('/rbac/rule/view', ['name' => $data->getName()]),
                                    [
                                        'class' => 'text-decoration-none mr-3',
                                    ]
                                );
                            })
                            ->update(function (Rule $data, int $index) use($urlGenerator) {
                                return Html::a(
                                    Icon::widget()->name('edit'),
                                    $urlGenerator->generate('/rbac/rule/edit', ['name' => $data->getName()]),
                                    [
                                        'class' => 'text-decoration-none mr-3',
                                    ]
                                );
                            })
                            ->delete(function (Rule $data, int $index) use($urlGenerator) {
                                return Html::a(
                                    Icon::widget()->name('delete'),
                                    $urlGenerator->generate('/rbac/rule/delete', ['name' => $data->getName()]),
                                    [
                                        'is' => 'link',
                                        'class' => 'text-decoration-none text-danger',
                                        'data' => [
                                            'method' => 'delete',
                                            'confirm' => 'Are you sure?',
                                        ],
                                    ]
                                );
                            }),
                    ]);
                ?>
            </div>
        </div><?php
        if ($paginator->getTotalCount() > 0) {
            ?><div class="mb-4"></div>
            <div class="row">
                <div class="col-6">
                    <?= GridView\OffsetSummary::widget()
                        ->paginator($paginator);
                    ?>
                </div>
                <div class="col-6">
                    <?= LinkPager::widget()
                        ->paginator($paginator)
                        ->options([
                            'class' => 'float-right'
                        ])
                        ->prevPageLabel('Previous')
                        ->nextPageLabel('Next')
                        ->urlGenerator(function (int $page) use($urlGenerator) {
                            $url = $urlGenerator->generate('/rbac/rule/index');
                            if ($page > 1) {
                                $url = $url . '?page=' . $page;
                            }

                            return $url;
                        });
                    ?>
                </div>
            </div><?php
        }
    ?></div>
</div>
