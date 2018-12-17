<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$center = 'Call Center';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $center ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?php //echo $this->Html->meta ( 'support.png', 'img/support.png', array ( 'type' => 'icon' ) );?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <?php 
        echo $this->Html->script('jquery-1.12.4');
        echo $this->Html->script('jquery.validate.min');
        echo $this->Html->script('additional-methods.min');
    ?>
</head>
<body>

    <?= $this->Flash->render() ?>
    
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>            

    <footer>
    </footer>
</body>
</html>
