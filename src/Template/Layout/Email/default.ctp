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
$act=$this->request->getParam('action');
$center = 'Call Center';
    
$navigation = [
    'work' => [
        'icon' => 'home',
        'title' => 'center',
        'sub-title' => 'センター',
        'default' => true,
        'url' => [
            'controller' => 'User',
            'action' => 'work'
        ]
    ],
    'home' => [
        'icon' => 'id-card-o',
        'title' => 'profile',
        'sub-title' => 'プロフィール',
        'default' => true,
        'url' => [
            'controller' => 'User',
            'action' => 'home'
        ]
    ],
    'index' => [
        'icon' => 'sliders',
        'title' => 'manage/setting',
        'sub-title' => '管理/設定',
        'url' => [
            'controller' => 'Services',
            'action' => 'index'
        ]
    ],
    'report' => [
        'icon' => 'bar-chart',
        'title' => 'report',
        'sub-title' => 'レポート',
        'url' => '',
        'childs' => [
            'report' => [
                'icon' => 'phone-square',
                'title' => 'center',
                'sub-title' => 'コールレポート',
                'url' => [
                    'controller' => 'User',
                    'action' => 'report'
                ]
            ],
            'reportByEmployee' => [
                'icon' => 'address-card',
                'title' => 'employee',
                'sub-title' => 'メンバーレポート',
                'url' => [
                    'controller' => 'User',
                    'action' => 'reportByEmployee'
                ]
            ]
        ]
    ],
    'showCallLog' => [
        'icon' => 'phone',
        'title' => 'history',
        'sub-title' => '通話履歴',
        'default' => true,
        'url' => [
            'controller' => 'User',
            'action' => 'showCallLog'
        ]
    ],
    'payment' => [
        'icon' => 'credit-card',
        'title' => 'payment',
        'sub-title' => 'お支払い',
        'url' => [
            'controller' => 'User',
            'action' => 'Payment'
        ]
    ]
];
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
    <?= $this->Html->css(['font-awesome.min', 'dashboard', 'bootstrap.min', 'custom']) ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <?php 
        echo $this->Html->script(['jquery-1.12.4', 'jquery.validate.min', 'additional-methods.min', 'bootstrap.bundle.min', 'dashboard']);
    ?>
</head>
<body>

    <?= $this->Flash->render() ?>
    <nav class="top-nav header navbar">
        <div class="container-fluid">
            <div class="pull-left">
                <?= $this->Html->link(
                    $this->Html->image('logo.png'),
                    'javascript:void(0)',
                    [
                        'class' => 'navbar-brand',
                        'role' => 'logo',
                        'aria-label' => 'logo',
                        'escape' => false
                    ])
                ?>
                <?= $this->Html->link(
                    '<i class="fa fa-bars"></i> <i class="fa fa-caret-down"></i>',
                    'javascript:void(0)',
                    [
                        'class' => 'btn btn-icon navbar-btn',
                        'data-sidebar' => 'toggleVisible',
                        'escape' => false
                    ])
                ?>
            </div>
            <div class="pull-right" role="navigation">
                <div class="dropdown-ext">
                    <?= $this->Html->link(
                        '<i class="fa fa-inbox"></i><span class="dotted dotted-danger"></span>',
                        'javascript:void(0)',
                        [
                            'class' => 'btn btn-icon navbar-btn dropdown-toggle',
                            'data-toggle' => 'dropdown',
                            'aria-label' => 'notifications',
                            'escape' => false
                        ])
                    ?>
                    <div class="dropdown-menu dropdown-menu-ext dropdown-menu-right" role="menu">
                    </div>
                </div>
                <div class="user-control dropdown">
                    <?= $this->Html->link(
                        $this->Html->image('user/'.$user->image),
                        'javascript:void(0)',
                        [
                            'class' => 'btn btn-icon navbar-btn dropdown-toggle',
                            'data-toggle' => 'dropdown',
                            'aria-expanded' => true,
                            'escape' => false
                        ]) 
                    ?>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        <li role="presentation">
                            <?= $this->Html->link(
                                '<span class="pull-right"><i class="fa fa-user text-muted"></i></span>Profile',
                                [
                                    'controller' => 'User', 
                                    'action' => 'home'
                                ],
                                [
                                    'role' => 'menuitem',
                                    'tabindex' => -1,
                                    'escape' => false
                                ]) 
                            ?>
                        </li>
                        <li class="divider"></li>
                        <li role="presentation">
                            <?= $this->Html->link(
                                '<span class="pull-right"><i class="fa fa-sign-out text-muted"></i></span>Sign out',
                                [
                                    'controller' => 'User', 
                                    'action' => 'logout'
                                ],
                                [
                                    'role' => 'menuitem',
                                    'tabindex' => -1,
                                    'escape' => false
                                ]) 
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class='wraper'>
        <aside class='left-bar'>
            <div class="left-bar-wellcome">
                <?php
                    echo $this->Html->image('user/'.$user->image, ['alt' => 'user image', 'class'=>'left-bar-user-image']);
                    echo $this->Html->tag('p', '<i>'.$user->company.'</i><br>'.$user->name, ['class'=>'user-name']);
                ?>
            </div>
            <div class='menu-list'>
                <ul>
                <?php
                foreach ($navigation as $key => $item) {
                    if($user->access==1 || (isset($item['default']) && $item['default'])) {
                        if($item['url']) {
                            ?>
                            <li class="li-center<?= $act == $key?' active':'' ?>">
                                <?= $this->Html->link(
                                    '<i class="fa fa-'.$item['icon'].'"></i><span class="upcase-text">'.$item['title'].'</span><span>'.$item['sub-title'].'</span>', 
                                    $item['url'],
                                    [
                                        'escape' => false
                                    ]);
                                ?>
                            </li>
                            <?php
                        }
                        else {
                            ?>
                            <li class="li-center<?= array_key_exists($act, $item['childs'])?' active':'' ?>">
                                <i class="fa fa-angle-down pull-right"></i>
                                <?= $this->Html->link(
                                    '<i class="fa fa-'.($item['icon']?$item['icon']:'circle').'"></i><span class="upcase-text">'.$item['title'].'</span><span>'.$item['sub-title'].'</span>', 
                                    '#'.$key,
                                    [
                                        'escape' => false,
                                        'data-toggle' => 'collapse'
                                    ]);
                                ?>
                            </li>

                            <ul id="<?= $key ?>" class="collapse">
                                <?php
                                foreach ($item['childs'] as $k => $child) {
                                    ?>
                                    <li class="li-center<?= $act == $k?' active':'' ?>" is-child>
                                        <?= $this->Html->link(
                                            '<i class="fa fa-'.$child['icon'].'"></i><span class="upcase-text">'.$child['title'].'</span><span>'.$child['sub-title'].'</span>', 
                                            $child['url'],
                                            [
                                                'escape' => false
                                            ]);
                                        ?>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <?php
                        }
                        
                    }
                }
                ?>
                    <li class="collapse-item">
                        <a href="#" data-sidebar="toggleCollapse">
                        <i class="fa fa-angle-double-left"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="main-content">
            <div class="container clearfix">
                <?= $this->fetch('content') ?>
            </div>            
        </div>
    </div>
    <footer>
    </footer>
</body>
</html>
