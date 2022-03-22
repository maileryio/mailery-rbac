<?php declare(strict_types=1);

use Mailery\Icon\Icon;
use Mailery\Widget\Link\Link;
use Yiisoft\Html\Html;
use Yiisoft\Rbac\Role;
use Yiisoft\Yii\DataView\GridView;

/** @var Yiisoft\Yii\WebView $this */
/** @var Yiisoft\Aliases\Aliases $aliases */
/** @var Yiisoft\Router\UrlGeneratorInterface $url */
/** @var Yiisoft\Data\Paginator\PaginatorInterface $paginator */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle('User roles');

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
            <h1 class="h3">User roles</h1>
            <div class="btn-toolbar float-right">
                <a class="btn btn-sm btn-primary mx-sm-1 mb-2" href="<?= $url->generate('/rbac/role/create'); ?>">
                    <?= Icon::widget()->name('plus')->options(['class' => 'mr-1']); ?>
                    Add new role
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
                    'value()' => [static function (Role $model, $index) use ($url) {
                        return Html::a(
                            $model->getName(),
                            $url->generate('/rbac/role/view', ['name' => $model->getName()])
                        );
                    }],
                ],
                [
                    'label()' => ['Rule'],
                    'value()' => [static function (Role $model, $index) use ($url) {
                        if (empty($model->getRuleName())) {
                            return $model->getRuleName();
                        }

                        return Html::a(
                            $model->getRuleName(),
                            $url->generate('/rbac/rule/view', ['name' => $model->getRuleName()])
                        );
                    }],
                ],
                [
                    'label()' => ['Description'],
                    'value()' => [fn (Role $model) => $model->getDescription()],
                ],
                [
                    'label()' => ['Edit'],
                    'value()' => [static function (Role $model, $index) use ($url) {
                        return Html::a(
                            Icon::widget()->name('pencil')->render(),
                            $url->generate('/rbac/role/edit', ['name' => $model->getName()]),
                            [
                                'class' => 'text-decoration-none mr-3',
                            ]
                        )->encode(false);
                    }],
                ],
                [
                    'label()' => ['Delete'],
                    'value()' => [static function (Role $model, $index) use ($csrf, $url) {
                        return Link::widget()
                            ->csrf($csrf)
                            ->label(Icon::widget()->name('delete')->options(['class' => 'mr-1'])->render())
                            ->method('delete')
                            ->href($url->generate('/rbac/role/delete', ['name' => $model->getName()]))
                            ->confirm('Are you sure?')
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
