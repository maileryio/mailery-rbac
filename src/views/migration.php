<?php
/**
 * @var string the new migration class name
 */
echo "<?php\n";
?>

use Mailery\Rbac\Migration;

class <?= $className; ?> extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "<?= $className; ?> cannot be reverted.\n";

        return false;
    }

}
