<?php declare(strict_types=1);

use Mailery\Icon\Icon;
use Mailery\Widget\Dataview\Columns\ActionColumn;
use Mailery\Widget\Dataview\Columns\DataColumn;
use Mailery\Widget\Dataview\GridView;
use Mailery\Widget\Dataview\GridView\LinkPager;
use Mailery\Widget\Link\Link;
use Yiisoft\Html\Html;
use Yiisoft\Rbac\Permission;

/** @var Mailery\Web\View\WebView $this */
/** @var Yiisoft\Aliases\Aliases $aliases */
/** @var Yiisoft\Router\UrlGeneratorInterface $urlGenerator */
/** @var Yiisoft\Data\Reader\DataReaderInterface $dataReader*/
/** @var Yiisoft\Data\Paginator\PaginatorInterface $paginator */
$this->setTitle('Access permissions');

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Access permissions</h1>
            <div class="btn-toolbar float-right">
                <form class="form-inline float-left">
                    <div class="input-group mx-sm-1 mb-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-outline-secondary" type="button">
                                <?= Icon::widget()->name('search'); ?>
                            </button>
                        </div>
                    </div>
                </form>
                <button class="btn btn-sm btn-secondary dropdown-toggle mb-2">
                    <?= Icon::widget()->name('settings'); ?>
                </button>
                <a class="btn btn-sm btn-primary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/permission/create'); ?>">
                    <?= Icon::widget()->name('plus')->options(['class' => 'mr-1']); ?>
                    Add new permission
                </a>
            </div>
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
                (new DataColumn())
                    ->header('Name')
                    ->content(function (Permission $data, int $index) use ($urlGenerator) {
                        return Html::a(
                            $data->getName(),
                            $urlGenerator->generate('/rbac/permission/view', ['name' => $data->getName()])
                        );
                    }),
                (new DataColumn())
                    ->header('Rule')
                    ->content(function (Permission $data, int $index) use ($urlGenerator) {
                        if (empty($data->getRuleName())) {
                            return $data->getRuleName();
                        }

                        return Html::a(
                            $data->getRuleName(),
                            $urlGenerator->generate('/rbac/rule/view', ['name' => $data->getRuleName()])
                        );
                    }),
                (new DataColumn())
                    ->header('Description')
                    ->content(function (Permission $data, int $index) {
                        return $data->getDescription();
                    }),
                (new ActionColumn())
                    ->contentOptions([
                        'style' => 'width: 80px;',
                    ])
                    ->header('Edit')
                    ->view('')
                    ->update(function (Permission $data, int $index) use ($urlGenerator) {
                        return Html::a(
                            (string) Icon::widget()->name('pencil'),
                            $urlGenerator->generate('/rbac/permission/edit', ['name' => $data->getName()]),
                            [
                                'class' => 'text-decoration-none mr-3',
                            ]
                        );
                    })
                    ->delete(''),
                (new ActionColumn())
                    ->contentOptions([
                        'style' => 'width: 80px;',
                    ])
                    ->header('Delete')
                    ->view('')
                    ->update('')
                    ->delete(function (Permission $data, int $index) use ($urlGenerator) {
                        return Link::widget()
                            ->label((string) Icon::widget()->name('delete')->options(['class' => 'mr-1']))
                            ->method('delete')
                            ->href($urlGenerator->generate('/rbac/permission/delete', ['name' => $data->getName()]))
                            ->confirm('Are you sure?')
                            ->options([
                                'class' => 'text-decoration-none text-danger',
                            ]);
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
                ->paginator($paginator); ?>
        </div>
        <div class="col-6">
            <?= LinkPager::widget()
                ->paginator($paginator)
                ->options([
                    'class' => 'float-right',
                ])
                ->prevPageLabel('Previous')
                ->nextPageLabel('Next')
                ->urlGenerator(function (int $page) use ($urlGenerator) {
                    $url = $urlGenerator->generate('/rbac/permission/index');
                    if ($page > 1) {
                        $url = $url . '?page=' . $page;
                    }

                    return $url;
                }); ?>
        </div>
    </div><?php
        }
?>
