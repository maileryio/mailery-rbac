<?php declare(strict_types=1);

use Mailery\Activity\Log\Widget\ActivityLogLink;
use Mailery\Icon\Icon;
use Mailery\Widget\Link\Link;
use Mailery\Web\Widget\DateTimeFormat;
use Yiisoft\Yii\Bootstrap5\Nav;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Yiisoft\Rbac\Role $role */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle($role->getName());

?><div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <h4 class="mb-0">Role #<?= $role->getName(); ?></h4>
                        <p class="mt-1 mb-0 small">
                            Changed at <?= DateTimeFormat::widget()->dateTime(
                                (new \DateTimeImmutable())->setTimestamp($role->getUpdatedAt())
                            ) ?>
                        </p>
                    </div>
                    <div class="col-auto">
                        <div class="btn-toolbar float-right">
                            <?= Link::widget()
                                ->csrf($csrf)
                                ->label(Icon::widget()->name('delete')->options(['class' => 'mr-1'])->render() . ' Delete')
                                ->method('delete')
                                ->href($url->generate('/rbac/role/delete', ['name' => $role->getName()]))
                                ->confirm('Are you sure?')
                                ->afterRequest(<<<JS
                                    (res) => {
                                        res.redirected && res.url && (window.location.href = res.url);
                                    }
                                    JS
                                )
                                ->options([
                                    'class' => 'btn btn-sm btn-danger mx-sm-1 mb-2',
                                ])
                                ->encode(false);
                            ?>
                            <a class="btn btn-sm btn-secondary mx-sm-1 mb-2" href="<?= $url->generate('/rbac/role/edit', ['name' => $role->getName()]); ?>">
                                <?= Icon::widget()->name('pencil')->options(['class' => 'mr-1']); ?>
                                Update
                            </a>
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
</div>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <?= Nav::widget()
                    ->currentPath($currentRoute->getUri()->getPath())
                    ->items([
                        [
                            'label' => 'Overview',
                            'url' => $url->generate('/rbac/role/view', ['name' => $role->getName()]),
                        ],
                        [
                            'label' => 'Edit',
                            'url' => $url->generate('/rbac/role/edit', ['name' => $role->getName()]),
                        ],
                    ])
                    ->options([
                        'class' => 'nav nav-tabs nav-tabs-bordered font-weight-bold',
                    ])
                    ->withoutEncodeLabels();
                ?>

                <div class="mb-4"></div>
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel">
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
