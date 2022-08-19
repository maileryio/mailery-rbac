<?php
declare(strict_types=1);

namespace Mailery\Rbac\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yiisoft\Rbac\AssignmentsStorageInterface;
use Yiisoft\Rbac\ItemsStorageInterface;
use Yiisoft\Rbac\Php\AssignmentsStorage;
use Yiisoft\Rbac\Php\ItemsStorage;
use Yiisoft\Yii\Console\ExitCode;
use Yiisoft\Composer\Config\Builder;
use Yiisoft\Rbac\Php\Storage;

class ResetCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'rbac/reset';

    /**
     * @param AssignmentsStorageInterface $assignmentsStorage
     * @param ItemsStorageInterface $itemsStorage
     */
    public function __construct(
        private AssignmentsStorageInterface $assignmentsStorage,
        private ItemsStorageInterface $itemsStorage
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
//        $config = new \Yiisoft\Config\Config(
//            new \Yiisoft\Config\ConfigPaths(dirname(__DIR__), 'config'),
//            $_ENV['YII_ENV'],
//            [
//                \Yiisoft\Config\Modifier\RecursiveMerge::groups('params', 'rbac-assignments', 'rbac-items', 'rbac-rules'),
//            ],
//        );
//
//        var_dump(
//            $config->get('rbac-assignments'),
//            $config->get('rbac-items'),
//            $config->get('rbac-rules')
//        );exit;
        $io = new SymfonyStyle($input, $output);

        try {
            $assignmentsStorage = new AssignmentsStorage(
                dirname(Builder::path('assignments')),
                'assignments.php'
            );

            $itemsStorage = new ItemsStorage(
                dirname(Builder::path('items')),
                'items.php'
            );

            $this->assignmentsStorage->clear();
            $this->itemsStorage->clear();

            foreach ($itemsStorage->getAll() as $item) {
                $this->itemsStorage->add($item);

                foreach ($itemsStorage->getChildren($item) as $children) {
                    $this->itemsStorage->addChild($item, $children);
                }
            }

            foreach ($assignmentsStorage->getAll() as $userId => $assignments) {
                foreach ($assignments as $assignment) {
                    $this->assignmentsStorage->add($assignment, $userId);
                }
            }

            $io->success('Rbac reset');
        } catch (\Throwable $t) {
            $io->error($t->getMessage());
            return $t->getCode() ?: ExitCode::UNSPECIFIED_ERROR;
        }
        return ExitCode::OK;
    }
}
