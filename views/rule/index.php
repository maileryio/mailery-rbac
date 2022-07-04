<?php declare(strict_types=1);

use Mailery\Icon\Icon;
use Mailery\Widget\Link\Link;
use Yiisoft\Html\Html;
use Yiisoft\Rbac\Rule;
use Yiisoft\Yii\DataView\GridView;

/** @var Yiisoft\Yii\WebView $this */
/** @var Yiisoft\Aliases\Aliases $aliases */
/** @var Yiisoft\Router\UrlGeneratorInterface $url */
/** @var Yiisoft\Data\Paginator\PaginatorInterface $paginator */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle('Access rules');

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
            <h1 class="h3">Access rules</h1>
            <div class="btn-toolbar float-right">
                <a class="btn btn-sm btn-primary mx-sm-1 mb-2" href="<?= $url->generate('/rbac/rule/create'); ?>">
                    <?= Icon::widget()->name('plus')->options(['class' => 'mr-1']); ?>
                    Add new rule
                </a>
            </div>
        </div>
    </div>
</div>
<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <?= GridView::widget()
            ->layout("{items}\n<div class=\"mb-4\"></div>\n{summary}\n<div class=\"float-right\">{pager}</div>")
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
            ->paginator($paginator)
            ->currentPage($paginator->getCurrentPage())
            ->columns([
                [
                    'label()' => ['Name'],
                    'value()' => [static function (Rule $data, $index) use ($url) {
                        return Html::a(
                            $data->getName(),
                            $url->generate('/rbac/rule/view', ['name' => $data->getName()])
                        );
                    }],
                ],
                [
                    'label()' => ['Edit'],
                    'value()' => [static function (Rule $data, $index) use ($url) {
                        return Html::a(
                            Icon::widget()->name('pencil')->render(),
                            $url->generate('/rbac/rule/edit', ['name' => $data->getName()]),
                            [
                                'class' => 'text-decoration-none mr-3',
                            ]
                        )->encode(false);
                    }],
                ],
                [
                    'label()' => ['Delete'],
                    'value()' => [static function (Rule $data, $index) use ($csrf, $url) {
                        return Link::widget()
                            ->csrf($csrf)
                            ->label(Icon::widget()->name('delete')->options(['class' => 'mr-1'])->render())
                            ->method('delete')
                            ->href($url->generate('/rbac/rule/delete', ['name' => $data->getName()]))
                            ->confirm('Are you sure?')
                            ->afterRequest(<<<JS
                                (res) => {
                                    res.redirected && res.url && (window.location.href = res.url);
                                }
                                JS
                            )
                            ->options([
                                'class' => 'text-decoration-none text-danger',
                            ])
                            ->encode(false);
                    }],
                ],
            ]);
        ?>
    </div>
</div>
