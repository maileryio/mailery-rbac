<?php
declare(strict_types=1);

namespace Mailery\Rbac\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yiisoft\Rbac\StorageInterface;
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
     * @param StorageInterface $storage
     */
    public function __construct(
        private StorageInterface $storage
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
//        );
        $io = new SymfonyStyle($input, $output);

        try {
            $defaultStorage = new Storage(
                dirname(Builder::path('rbac-assignments')),
                'rbac-items.php',
                'rbac-assignments.php',
                'rbac-rules.php'
            );

            $this->storage->clear();

            foreach ($defaultStorage->getItems() as $item) {
                $this->storage->addItem($item);
            }

            foreach ($defaultStorage->getRules() as $rule) {
                $this->storage->addRule($rule);
            }

            foreach ($defaultStorage->getAssignments() as $userId => $assignments) {
                foreach ($assignments as $assignment) {
                    $this->storage->addAssignment($userId, $assignment);
                }
            }

            foreach ($defaultStorage->getChildren() as $itemName => $children) {
                $parent = $this->storage->getItemByName($itemName);

                foreach ($children as $child) {
                    $this->storage->addChild($parent, $child);
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
