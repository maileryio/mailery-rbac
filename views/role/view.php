<?php declare(strict_types=1);

use Mailery\Icon\Icon;
use Mailery\Widget\Dataview\DetailView;
use Mailery\Widget\Link\Link;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Role $role */
/** @var Yiisoft\Yii\View\Csrf $csrf */
/** @var bool $submitted */

$this->setTitle($role->getName());

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
            <h1 class="h3">Role #<?= $role->getName(); ?></h1>
            <div class="btn-toolbar float-right">
                <?= Link::widget()
                    ->label(Icon::widget()->name('delete')->options(['class' => 'mr-1'])->render() . ' Delete')
                    ->method('delete')
                    ->href($urlGenerator->generate('/rbac/role/delete', ['name' => $role->getName()]))
                    ->confirm('Are you sure?')
                    ->options([
                        'class' => 'btn btn-sm btn-danger mx-sm-1 mb-2',
                    ])
                    ->encode(false);
                ?>
                <a class="btn btn-sm btn-secondary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/role/edit', ['name' => $role->getName()]); ?>">
                    <?= Icon::widget()->name('pencil')->options(['class' => 'mr-1']); ?>
                    Update
                </a>
                <div class="btn-toolbar float-right">
                    <a class="btn btn-sm btn-outline-secondary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/rbac/role/index'); ?>">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
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
    <div class="col-12">
        <ui-dual-treeview
            csrf-name="<?= $csrf->getParameterName(); ?>"
            csrf-value="<?= $csrf->getToken(); ?>"
            fetch-assigned-url="<?= $urlGenerator->generate('/rbac/assigned', ['name' => $role->getName(), 'type' => $role->getType()]); ?>"
            fetch-unassigned-url="<?= $urlGenerator->generate('/rbac/unassigned', ['name' => $role->getName(), 'type' => $role->getType()]); ?>"
            post-assign-url="<?= $urlGenerator->generate('/rbac/assign', ['name' => $role->getName(), 'type' => $role->getType()]); ?>"
            post-unassign-url="<?= $urlGenerator->generate('/rbac/unassign', ['name' => $role->getName(), 'type' => $role->getType()]); ?>"
        >
            <template v-slot:assign-button-content>
                <?= Icon::widget()->name('chevron-right');?>
            </template>
            <template v-slot:unassign-button-content>
                <?= Icon::widget()->name('chevron-left');?>
            </template>
        </ui-dual-treeview>
    </div>
</div>
