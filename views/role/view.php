<?php declare(strict_types=1);

use Mailery\Icon\Icon;
use Mailery\Widget\Dataview\DetailView;
use Yiisoft\Yii\Widgets\ContentDecorator;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Role $role */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle($role->getName());

?>

<?= ContentDecorator::widget()
    ->viewFile('@vendor/maileryio/mailery-rbac/views/role/_layout.php')
    ->parameters(compact('role', 'csrf'))
    ->begin(); ?>

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
            csrf-value="<?= $csrf->getToken(); ?>"
            csrf-header-name="<?= $csrf->getHeaderName(); ?>"
            fetch-assigned-url="<?= $url->generate('/rbac/assigned', ['name' => $role->getName(), 'type' => $role->getType()]); ?>"
            fetch-unassigned-url="<?= $url->generate('/rbac/unassigned', ['name' => $role->getName(), 'type' => $role->getType()]); ?>"
            post-assign-url="<?= $url->generate('/rbac/assign', ['name' => $role->getName(), 'type' => $role->getType()]); ?>"
            post-unassign-url="<?= $url->generate('/rbac/unassign', ['name' => $role->getName(), 'type' => $role->getType()]); ?>"
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

<?= ContentDecorator::end() ?>