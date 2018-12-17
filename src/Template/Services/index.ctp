<?php
    $this->assign('title', 'Time services');
    echo $this->Html->script('jquery-ui-1.10.4.custom');
    echo $this->Html->css(['timerange','switch','tabchange']);

    $week = [
        'Monday' => [
            'value' => isset($timeSrv)?$timeSrv->monday:'',
            'label' => '月'
        ],
        'Tuesday' => [
            'value' => isset($timeSrv)?$timeSrv->tuesday:'',
            'label' => '火'
        ],
        'Wednesday' => [
            'value' => isset($timeSrv)?$timeSrv->wednesday:'',
            'label' => '水'
        ],
        'Thursday' => [
            'value' => isset($timeSrv)?$timeSrv->thursday:'',
            'label' => '木'
        ],
        'Friday' => [
            'value' => isset($timeSrv)?$timeSrv->friday:'',
            'label' => '金'
        ],
        'Saturday' => [
            'value' => isset($timeSrv)?$timeSrv->saturday:'',
            'label' => '土'
        ],
        'Sunday' => [
            'value' => isset($timeSrv)?$timeSrv->sunday:'',
            'label' => '日'
        ],
        'Holiday' => [
            'value' => isset($timeSrv)?$timeSrv->holiday:'',
            'label' => '休'
        ]
    ];
    $time=time();

?>
<div class="content-bar">
    <h2><span>MANAGE</span>管理</h2>
</div>
<div class="content">
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <big>
                            <i class="fa fa-sliders"></i>&nbsp;
                            <span class="upcase-text">working time</span>
                            <small>&nbsp;営業日時設定</small>
                        </big>
                    </h3>
                    <hr>
                </div>
                <div class="panel-body">
                    <?php
                    foreach ($week as $key => $day) {
                        if($day['value']){
                            $str=explode('-', $day['value']);
                            $from=$str[0];
                            $to=$str[1];
                        }
                        else{
                            $from='10:00';
                            $to='15:00';
                        }
                        ?>
                        <div class="form-group">
                            <label>
                                <div class="nice-checkbox nice-checkbox-inline">
                                    <?= $this->Form->checkbox(
                                        'none', 
                                        [
                                            'hiddenField' => false,
                                            'class' => 'checkbox-o',
                                            'id' => $key.'-check',
                                            'checked' => $day['value']!='',
                                            'onclick' => 'checkdisable("'.$key.'")'
                                        ]
                                    )
                                    ?>
                                    <label><?= $day['label'] ?></label>
                                </div>
                                <small>
                                    <span class="slider-time-<?= $key ?>"><?php echo $from?></span> - 
                                    <span class="slider-time2-<?= $key ?>"><?php echo $to?></span>
                                </small>
                            </label>
                            <p class="slider-range-<?= $key ?><?= $day['value']!=''?'':' disabled-range' ?>"></p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-control pull-right">
                        <label class="switch" title="自動応答アナウンス">
                            <?= $this->Form->checkbox(
                                'none',
                                [
                                    'class' => 'check-active',
                                    'onclick' => 'changeStatusRec()',
                                    'id' => 'changeStatusRec',
                                    'checked' => $timeSrv->timeoutRec!=''|| $timeSrv->timeinRec !='' || $timeSrv->busyRec !=''
                                ]
                            )
                            ?>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <h3 class="panel-title">
                        <big>
                            <i class="fa fa-microphone"></i>&nbsp;
                            <span class="upcase-text">record file</span>
                            <small>&nbsp;録音ファイルのアップロード</small>
                        </big>
                    </h3>
                </div>
                <?php
                if($timeSrv->timeoutRec!=''|| $timeSrv->timeinRec !='' || $timeSrv->busyRec !='') {
                    ?>
                    <div class="panel-body">
                        <div class="panel-control">
                            <ul class="nav nav-tabs nav-contrast-dark">
                                <li>
                                    <?php
                                    if($activeTab==1){
                                        echo $this->Html->link(
                                            '時間外',
                                            '#tab-1',
                                            [
                                                'data-toggle' => 'tab',
                                                'class' => 'active'
                                            ]
                                        );  
                                    }else{
                                        echo $this->Html->link(
                                            '時間外',
                                            '#tab-1',
                                            [
                                                'data-toggle' => 'tab',
                                            ]
                                        );  
                                    } 
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        if($activeTab==2){
                                            echo $this->Html->link(
                                                '営業時間',
                                                '#tab-2',
                                                [
                                                    'data-toggle' => 'tab',
                                                    'class' => 'active'
                                                ]
                                            );
                                        }else{
                                            echo $this->Html->link(
                                                '営業時間',
                                                '#tab-2',
                                                [
                                                    'data-toggle' => 'tab'
                                                ]
                                            );
                                        }                                     
                                    ?>
                                    
                                </li>
                                <li>
                                    <?php
                                    if($activeTab==3){
                                        echo $this->Html->link(
                                            '混雑時',
                                            '#tab-3',
                                            [
                                                'data-toggle' => 'tab',
                                                'class' => 'active'
                                            ]
                                        );
                                    }else{
                                        echo $this->Html->link(
                                            '混雑時',
                                            '#tab-3',
                                            [
                                                'data-toggle' => 'tab',
                                            ]
                                        );
                                    }
                                     
                                    ?>
                                    
                                </li>
                            </ul>
                        </div>
                            <div class="tab-content">
                                <div class="tab-pane <?php if($activeTab==1){echo 'active';}?>" id="tab-1">
                                    <?php clearstatcache();?>
                                    <audio id="player" class='audio audio-timeoutRec' controls>
                                        <source src='<?= "http://".$_SERVER['HTTP_HOST']."/audio/".$timeSrv->timeoutRec."?play=".$time ?>' type="audio/ogg">
                                        Your browser does not support the audio element.
                                    </audio>
                                    <?= $timeSrv->timeoutRec?>
                                    <?= $this->Form->create(
                                        $timeSrv,
                                        [
                                            'type'=>'file',
                                            'url' => ['action' => 'index'],
                                            'id'=>'up-rec-file-timeout',

                                        ])
                                    ?>
                                    <div class="form-group top-10">
                                        <label class="control-label">ファイル選択（mp3ファイル、1MB未満）</label>
                                        <div class="input-group input-group-in">
                                            <span class="input-group-addon"><i class="fa fa-file-audio-o"></i></span>
                                            <span class="upload-file form-control">
                                                <span>Choose file...</span>
                                                <?php 
                                                    echo $this->Form->input(
                                                        'filerec', 
                                                        [
                                                            'type' => 'file',
                                                            'class' => 'upload_rec',
                                                            'label'=>false,
                                                            'data-trigger' => 'fileinput',
                                                            'templates' => ['inputContainer' => '{{content}}']
                                                        ]
                                                    );
                                                    echo $this->Form->input(
                                                            'tabFlag', 
                                                            [
                                                                'type' => 'hidden',
                                                                'value' => 1
                                                            ]
                                                    );
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?= $this->Form->input(
                                        'time', 
                                        [
                                            'type' => 'hidden', 
                                            'value'=>'timeoutRec'
                                        ])
                                    ?>
                                    <br>
                                    <div class="pull-right">
                                        <?= $this->Form->button(
                                            '<i class="fa fa-check"></i>&nbsp;アップロード',
                                            [
                                                'type' => 'submit',
                                                'class' => 'button _blue _border',
                                                'escape' => false
                                            ]
                                        )
                                        ?>
                                    </div>
                                    <div class="pull-left">
                                        <?= $this->Html->link(
                                            '<i class="fa fa-times"></i>&nbsp;削除',
                                            [
                                                'controller' => 'services', 
                                                'action' => 'deleteRecord/timeout'
                                            ],
                                            [
                                                'class' => 'button _red _border',
                                                'escape' => false
                                            ]
                                        )
                                        ?>
                                    </div>
                                    <?= $this->Form->end() ?>
                                </div>
                                <div class="tab-pane <?php if($activeTab==2){echo 'active';}?>" id="tab-2">
                                    <?php clearstatcache();?>
                                    <audio id="player" class='audio audio-timeoutRec' controls>
                                        <source  src='<?= "http://".$_SERVER['HTTP_HOST']."/audio/".$timeSrv->timeinRec."?play=".$time ?>' type="audio/ogg">
                                        Your browser does not support the audio element.
                                    </audio>
                                    <?= $timeSrv->timeinRec?>
                                    <?= $this->Form->create(
                                        $timeSrv,
                                        [
                                            'type'=>'file',
                                            'url' => ['action' => 'index'],
                                            'id'=>'up-rec-file-timein'
                                        ])
                                    ?>
                                    <div class="form-group top-10">
                                        <label class="control-label">ファイル選択（mp3ファイル、1MB未満）</label>
                                        <div class="input-group input-group-in">
                                            <span class="input-group-addon"><i class="fa fa-file-audio-o"></i></span>
                                            <span class="upload-file form-control">
                                                <span>Choose file...</span>
                                                <?php 
                                                    echo $this->Form->input(
                                                        'filerec', 
                                                        [
                                                            'type' => 'file',
                                                            'class' => 'upload_rec',
                                                            'label'=>false,
                                                            'data-trigger' => 'fileinput',
                                                            'templates' => ['inputContainer' => '{{content}}']
                                                        ]
                                                    );
                                                   
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?= $this->Form->input(
                                        'time', 
                                        [
                                            'type' => 'hidden', 
                                            'value'=>'timeinRec'
                                        ])
                                    ?>
                                    <br>
                                    <div class="pull-right">
                                        <?= $this->Form->button(
                                            '<i class="fa fa-check"></i>&nbsp;アップロード',
                                            [
                                                'type' => 'submit',
                                                'class' => 'button _blue _border',
                                                'escape' => false
                                            ]
                                        )
                                        ?>
                                    </div>
                                    <div class="pull-left">
                                        <?= $this->Html->link(
                                            '<i class="fa fa-times"></i>&nbsp;削除',
                                            [
                                                'controller' => 'services', 
                                                'action' => 'deleteRecord/timein'
                                            ],
                                            [
                                                'class' => 'button _red _border',
                                                'escape' => false
                                            ]
                                        )
                                        ?>
                                    </div>
                                    <?= $this->Form->end() ?>
                                </div>

                                <div class="tab-pane <?php if($activeTab==3){echo 'active';}?>" id="tab-3">
                                    <?php clearstatcache();?>
                                    <audio id="player" class='audio audio-timeoutRec' controls>
                                        <source  src='<?= "http://".$_SERVER['HTTP_HOST']."/audio/".$timeSrv->busyRec."?play=".$time ?>' type="audio/ogg">
                                        Your browser does not support the audio element.
                                    </audio>
                                     <?= $timeSrv->busyRec?>
                                    <?= $this->Form->create(
                                        $timeSrv,
                                        [
                                            'type'=>'file',
                                            'url' => ['action' => 'index'],
                                            'id'=>'up-rec-file-busy'
                                        ])
                                    ?>
                                    <div class="form-group top-10">
                                        <label class="control-label">ファイル選択（mp3ファイル、1MB未満）</label>
                                        <div class="input-group input-group-in">
                                            <span class="input-group-addon"><i class="fa fa-file-audio-o"></i></span>
                                            <span class="upload-file form-control">
                                                <span>Choose file...</span>
                                                <?php 
                                                    echo $this->Form->input(
                                                        'filerec', 
                                                        [
                                                            'type' => 'file',
                                                            'class' => 'upload_rec',
                                                            'label'=>false,
                                                            'data-trigger' => 'fileinput',
                                                            'templates' => ['inputContainer' => '{{content}}']
                                                        ]
                                                    );
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?= $this->Form->input(
                                        'time', 
                                        [
                                            'type' => 'hidden', 
                                            'value'=>'busy'
                                        ])
                                    ?>
                                    <br>
                                    <div class="pull-right">
                                        <?= $this->Form->button(
                                            '<i class="fa fa-check"></i>&nbsp;アップロード',
                                            [
                                                'type' => 'submit',
                                                'class' => 'button _blue _border',
                                                'escape' => false
                                            ]
                                        )
                                        ?>
                                    </div>
                                    <div class="pull-left">
                                        <?= $this->Html->link(
                                            '<i class="fa fa-times"></i>&nbsp;削除',
                                            [
                                                'controller' => 'services', 
                                                'action' => 'deleteRecord/busy'
                                            ],
                                            [
                                                'class' => 'button _red _border',
                                                'escape' => false
                                            ]
                                        )
                                        ?>
                                    </div>
                                    <?= $this->Form->end() ?>
                                </div>
                        </div>
                    </div>

                    <?php
                }
                ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-control pull-right">
                        <div class="dropdown-ext">
                            <?= $this->Html->link(
                                '<i class="fa fa-user-plus"></i>',
                                'javascript:void(0)',
                                [
                                    'class' => 'btn btn-icon navbar-btn dropdown-toggle',
                                    'data-toggle' => 'dropdown',
                                    'aria-label' => 'Add new member',
                                    'escape' => false
                                ]
                            )
                            ?>                   
                            <div class="dropdown-menu dropdown-menu-ext dropdown-menu-right dropdown-menu-member" role="add-member">
                                <?= $this->Form->create(
                                    'log', 
                                    [
                                        'url'=>['action'=>'employee','controller'=>'user'], 
                                        'id'=>'user-add-employee'
                                    ]
                                )
                                ?>
                                <div>
                                    <h3>メンバー追加</h3>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="input-group input-group-in">
                                                <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                                <?= $this->Form->text(
                                                    'email',
                                                    [
                                                        'class' => 'form-control',
                                                        'placeholder' => 'メールアドレス'
                                                    ]
                                                )
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group input-group-in">
                                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                <?= $this->Form->text(
                                                    'name',
                                                    [
                                                        'class' => 'form-control',
                                                        'placeholder' => '名前'
                                                    ]
                                                )
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?= $this->Form->button(
                                                'Add',
                                                [
                                                    'id'=>'employee-add-button',
                                                    'class' => 'button _green'
                                                ]
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?= $this->Form->end() ?>
                            </div>
                        </div>
                    </div>
                    <h3 class="panel-title">
                        <big>
                            <i class="fa fa-users"></i>&nbsp;
                            <span class="upcase-text">members</span>
                            <small>&nbsp;メンバー</small>
                        </big>
                    </h3>
                    <hr>
                </div>
                <div class="panel-body">
                    <div data-toggle="slimScroll">
                        <ul class="media-list">
                            <?php
                            //echo '<pre>';print_r($emp);echo '</pre>';exit;
                            foreach ($emp as $key => $value) {
                                ?>
                                <li class="media" id="employee-<?= $key ?>">
                                    <div class="media-left<?= $value['User__confirm']!='1'?' notify _warning" title="not confirm':'' ?>">
                                        <?= $this->Html->image(
                                            'user/'.$value['User__image'],
                                            [
                                                'class' => 'status-icon'
                                            ]
                                        )
                                        ?>
                                    </div>
                                    <div class="media-body">
                                        <small class="pull-right">
                                            <label class="switch" title="自動応答アナウンス">
                                                <?= $this->Form->checkbox(
                                                    'none',
                                                    [
                                                        'class' => 'check-active',
                                                        'onclick' => 'changeActive(\''.$value['User__email'].'\')',
                                                        'id' => str_replace('@', '', $value['User__email']),
                                                        'checked' => $value['User__active']==1
                                                    ]
                                                )
                                                ?>
                                                <span class="slider round"></span>
                                            </label>
                                            &nbsp;
                                            <i class=" trash-icon fa fa-trash-o" onclick="deleteEmployee('<?= $value['User__email'] ?>', '<?= $key ?>')" title="delete employee" is-link></i>
                                        </small>
                                        <p class="media-heading top-0">
                                            <?= $value['User__name'] ?>
                                        </p>
                                        <p class="help-block">
                                            <?= $this->Html->link(
                                                $value['User__email'],
                                                'mailto:'.$value['User__email']
                                            )
                                            ?>
                                        </p>
                                  </div>
                                </li>
                                <?php
                            }
                            ?>
                            
                        </ul>
                    </div>
                </div>

        </div>
    </div>
</div>
<script type="text/javascript">
<?php
    foreach ($week as $key => $day) {
        if($day['value']){
            $str=explode('-', $day['value']);
            $slidefrom=explode(':', $str[0]);
            $slidefrom=$slidefrom[0]*60+$slidefrom[1];
            $slideto=explode(':', $str[1]);
            $slideto=$slideto[0]*60+$slideto[1];
        }
        else{
            $slidefrom='600';
            $slideto='900';
        }
        ?>
        $(".slider-range-<?= $key ?>").slider({
            range: true,
            min: 0,
            max: 1440,
            step:30,
            values: ['<?php echo $slidefrom?>', '<?php echo $slideto?>'],
            slide: function (e, ui) {
                var hours1 = Math.floor(ui.values[0] / 60);
                var minutes1 = ui.values[0] - (hours1 * 60);

                if (hours1.length == 1) hours1 = '0' + hours1;
                if (minutes1.length == 1) minutes1 = '0' + minutes1;
                if (minutes1 == 0) minutes1 = '00';   
                $('.slider-time-<?= $key ?>').html(hours1 + ':' + minutes1);

                var hours2 = Math.floor(ui.values[1] / 60);
                var minutes2 = ui.values[1] - (hours2 * 60);

                if (hours2.length == 1) hours2 = '0' + hours2;
                if (minutes2.length == 1) minutes2 = '0' + minutes2;
                if (minutes2 == 0) minutes2 = '00';
                
                $('.slider-time2-<?= $key ?>').html(hours2 + ':' + minutes2);
                var val=hours1 + ':' + minutes1 +'-'+hours2 + ':' + minutes2;
                var data = { 'dayinweek': '<?= $key ?>', 'accountSid':'<?php echo $user->accountSid;?>','val':val};
                $.ajax({
                    type:"POST",
                    cache:false,
                    url:'/Services/updateTimeService',
                    data :data,
                    success: function (data) { 
                    
                    }
                });
            }
        });
        <?php
    }
?>

</script>
<script type="text/javascript">

    function checkdisable(dayinweek){
        
        var val='';
        if($('#'+dayinweek+'-check').is(':checked')){           
            $('.slider-range-'+dayinweek).removeClass('disabled-range');
            $('.show-p-'+dayinweek).removeClass('delete-line');
           
            val='10:00-15:00';
        }else{  
            $('.slider-range-'+dayinweek).addClass('disabled-range');
            $('.show-p-'+dayinweek).addClass('delete-line');
           
        }

        var data = { 'dayinweek': dayinweek, 'accountSid':'<?php echo $user->accountSid;?>','val':val};
        $.ajax({
            type:"POST",
            cache:false,
            url:'/Services/updateTimeService',
            data :data,
            success: function (data) { 
                $(".slider-range-"+dayinweek).slider({
                    range: true,
                    min: 0,
                    max: 1440,
                    step: 30,
                    values: [600, 900],
                });
                $('.slider-time-'+dayinweek).html('10:00');
                $('.slider-time2-'+dayinweek).html('15:00');
            }
        });
    }
</script>

 <script>
    $( function() {
        $( "#tabs" ).tabs();
    } );

    function changeStatusRec(){
        
        if( document.getElementById('changeStatusRec').checked == true){
            $(location).attr('href', '/services/changeStatusRec/0');
        }else{
            $(location).attr('href', '/services/changeStatusRec/1');
        } 
    }

</script>
<script type="text/javascript">
$(document).ready(function() {
 
    $("#up-rec-file-timeout").validate({
        rules: {
            filerec:{
                extension: 'mp3',
                required:true,
            },
        },
        
    });

    $("#up-rec-file-timein").validate({
        rules: {
            filerec:{
                extension: 'mp3',
                required:true,
            },
        },
        
    });

    $("#up-rec-file-busy").validate({
        rules: {
            filerec:{
                extension: 'mp3',
                required:true,
            },
        },
        
    });

    $("#user-add-employee").validate({
        rules: {
            email:{
                required: true,
                email: true
            },
            name:{
                required: true,
            },
        },
        
    });
});
function changeActive(mail){
    var act=0;
    var id= mail.replace("@", "");
    if( document.getElementById(id).checked == true){
        act=1;
    } else {
        act=2;
    }
    var data={ 'email': mail , 'act': act};
    $.ajax({
        type:"POST",
        cache:false,
        url:'/User/setactive',
        data :data,
        success: function (data) { 
                         
        }
    });
}
function deleteEmployee(mail,index){
    if(confirm('Do you want to delete employee '+mail+'?')) {
        $.ajax({
            type:"POST",
            cache:false,
            url:'/User/deleteEmployee',
            data :{ 'email': mail },
            success: function (data) { 
                $('#employee-'+index)[0].remove();
            }
        });
    }
    
}
</script>