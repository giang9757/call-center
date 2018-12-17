
<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

$pos = strpos($message, '/2010-04-01/Accounts/');
 
if ($pos !== false) {

    $this->layout=false;
    echo '<body style="margin: 0;padding: 0">';
    echo $this->Html->image('79139.jpg',['width'=>'100%','height'=>'100%']);
    echo '</body>';
    
} else {
    $this->layout = 'error';
    if (Configure::read('debug')) :
        $this->layout = 'dev_error';

        $this->assign('title', $message);
        $this->assign('templateName', 'error500.ctp');

        $this->start('file');
?>
    <?php if (!empty($error->queryString)) : ?>
        <p class="notice">
            <strong>SQL Query: </strong>
            <?= h($error->queryString) ?>
        </p>
    <?php endif; ?>
    <?php if (!empty($error->params)) : ?>
            <strong>SQL Query Params: </strong>
            <?php Debugger::dump($error->params) ?>
    <?php endif; ?>
    <?php if ($error instanceof Error) : ?>
            <strong>Error in: </strong>
            <?= sprintf('%s, line %s', str_replace(ROOT, 'ROOT', $error->getFile()), $error->getLine()) ?>
    <?php endif; ?>
    <?php
        echo $this->element('auto_table_warning');

        if (extension_loaded('xdebug')) :
            xdebug_print_function_stack();
        endif;

        $this->end();
    endif;
    ?>
    <h2><?= __d('cake', 'An Internal Error Has Occurred') ?></h2>
    <p class="error">
        <strong><?= __d('cake', 'Error') ?>: </strong>
        <?= h($message) ?>
    </p>
<?php }?>