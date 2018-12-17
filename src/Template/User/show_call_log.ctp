<?php
	$this->assign('title', 'Call log');
	echo $this->Html->charset();
    echo $this->Html->css(['callcenter', 'components']);
    echo $this->Html->script(['twilio.min', 'moment', 'daterangepicker', 'morris.bundle', 'jquery.sparkline', 'jquery.easypiechart']);
    $statisticBy = 'month';
  
?>
<div class="content-bar">
    <h2><span>HISTORY</span> 通話履歴</h2>
</div>
<div class="content row">
    <div class="col-md-6" id='phone-key'>
        <div class="panel" data-fill-color="true">
            <div class="panel-heading">
                <div class="panel-control pull-right">
                    <?= $this->Html->link(
                        '今日',
                        '/user/showCallLog/d',
                        [
                            'type' => 'day',
                            'class' => 'btn btn-default _set-date'
                        ]
                    )
                    ?>
                    <?= $this->Html->link(
                        '今週',
                        '/user/showCallLog/w',
                        [
                            'type' => 'week',
                            'class' => 'btn btn-default _set-date'
                        ]
                    )
                    ?>
                    <?= $this->Html->link(
                        '今月',
                        '/user/showCallLog/m',
                        [
                            'type' => 'month',
                            'class' => 'btn btn-default _set-date'
                        ]
                    )
                    ?>
                </div>
                <h3 class="panel-title">
                    <big>
                        <i class="fa fa-search"></i>&nbsp;
                        <span class="upcase-text">Search</span>
                        <small>&nbsp;検索</small>
                    </big>
                </h3>
            </div>
            <div class="panel-body">
                <?= $this->Form->create(
                    'log', 
                    [
                        'url'=>['action'=>'showCallLog'], 
                        'id'=>'filter-form'
                    ]
                )
                ?>
                <div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>From</label>
                                    <div class="input-group input-group-in">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <?= $this->Form->text(
                                            'from',
                                            [
                                                'data-input' => 'daterangepicker',
                                                'data-single-date-picker' => 'true',
                                                'data-show-dropdowns' => 'true',
                                                'class' => 'form-control',
                                                'id' => 'dateIn',
                                                'value' =>$from,
                                            ]
                                        )
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>To</label>
                                    <div class="input-group input-group-in">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <?= $this->Form->text(
                                            'to',
                                            [
                                                'data-input' => 'daterangepicker',
                                                'data-single-date-picker' => 'true',
                                                'data-show-dropdowns' => 'true',
                                                'class' => 'form-control',
                                                'id' => 'dateOut',
                                                'value' =>$to,
                                            ]
                                        )
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right">
                        <div class="form-group">
                            <?= $this->Form->button(
                                'Search',
                                [
                                    'class' => 'button _green _inline'
                                ]
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
        <div class="panel" data-fill-color="true">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <big>
                        <i class="fa fa-pie-chart"></i>&nbsp;
                        <span class="upcase-text">statistics</span>
                        <small>&nbsp;コール統計</small>
                    </big>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="help-block">
                            <div id="graph-in" class="morris-chart"></div>
                        </div>
                        <div>
                            <span class="text-overflow">受信件数</span>
                        </div>
                        <p class="fa-2x"><?php echo $countCallStatus['ans-busy']+$countCallStatus['ans-completed']+$countCallStatus['ans-no-answer'];?>件</p>
                        <div class="progress progress-xs">
                            <div class="progress-bar progress-bar-info" style="width:50%"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="help-block">
                            <div id="graph-out" class="morris-chart"></div>
                        </div>
                        <div>
                            <span class="text-overflow">発信件数</span>
                        </div>
                        <p class="fa-2x"><?php echo $countCallStatus['call-busy']+$countCallStatus['call-completed']+$countCallStatus['call-no-answer'];?>件</p>
                        <div class="progress progress-xs">
                            <div class="progress-bar progress-bar-success" style="width:82%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class=" col-md-6" id='call-log-data'>
        <div class="panel" data-fill-color="true">
            <div class="panel-heading clear">
                <h3 class="panel-title float-left">
                    <big>
                        <i class="fa fa-list-ul"></i>&nbsp;
                        <span class="upcase-text">detail</span>
                        <small>&nbsp;詳細コール</small>
                    </big>
                </h3>
                <?= $this->Html->image('full-screen.jpg',['title'=>'full icon','id'=>'full-screen-image','onclick' => 'full()'])?>
                <?= $this->Html->image('2815-200.png',['title'=>'full icon','id'=>'exit-full-screen-image','onclick' => 'exitFull()'])?>
            </div>
            <div class="panel-body">
                <div data-toggle="slimScroll">
                    <ul class="media-list">
                        <?php
                        foreach ($callData as $call) {
                           if($call['Memocaller__duration']>60){
                               
                                $s=$call['Memocaller__duration']%60;
                                $p=($call['Memocaller__duration']-$s)/60 ;
                                $call['Memocaller__duration']=$p.'分'.$s;
                           }
                        ?>
                            <li class="media li-callog">
                                <?php echo $this->Form->create(
                                        'memo-form', 
                                        [
                                            'url'=>['action'=>'saveMemo','controller'=>'user'], 
                                            
                                        ]
                                    );
                                echo $this->Form->input('accountSid', array('type'=>'hidden','value'=>$user->accountSid));
                                echo $this->Form->input('callid', array('type'=>'hidden','value'=>$call['Memocaller__callId']));
                                echo $this->Form->input('returnFlag', array('type'=>'hidden','value'=> 1));
                                ?>
                                <div class="media-left">
                                    <?= $this->Html->image(
                                        $call['Memocaller__status'].'.png', 
                                        [
                                            'alt' => $call['Memocaller__status'],
                                            'title' => $call['Memocaller__status'],
                                            'class'=>'status-icon'
                                        ]);
                                    ?>
                                </div>
                                <div class="media-body">
                                    <p class="pull-right">
                                        <small>
                                            <?= date('m/d H:i', strtotime( $call['Memocaller__date'])) ?>
                                            <span class="flag <?= $call['Memocaller__duration']>0?'_red':'_gray' ?>">
                                                <?= $call['Memocaller__duration'] ?> 秒
                                            </span>
                                        </small>
                                        
                                    </p>
                                    <p class="media-heading">
                                        <span onclick="call('<?= str_replace('+81', '0', $call['Memocaller__callFrom'])  ?>')" class="p_call">
                                            <?php
                                                if($call['Memocaller__image']!=''){
                                                    echo $call['Memocaller__image'];
                                                }else{
                                                    if($call['Memocaller__callFrom']==$user->callerId ){
                                                        echo 'Center';
                                                    }else{
                                                        echo str_replace('+81', '0', $call['Memocaller__callFrom']);
                                                    }
                                                    
                                                }
                                                 
                                            ?>
                                        </span>
                                        <!-- <i class="fa fa-angle-right"></i> -->
                                        <?= $this->Html->image('simple-arrow-hi.png', ['alt' => 'call arrow', 'class'=>'call-arrow']);?>
                                        <?php 
                                            if($call['Memocaller__callTo']==$user->callerId || $call['Memocaller__callTo']==''){
                                                 echo 'Center';
                                            }else{
                                                echo str_replace('+81', '0', $call['Memocaller__callTo']);
                                            }
                                            
                                        ?>
                                    </p>
                                    <p class="help-block">
                                        
                                        <?php
                                        if($call['Memocaller__orderId']!=0){
                                            echo '伝票番号：　'.$this->Html->link(
                                                $call['Memocaller__orderId'],
                                                'https://ne06.next-engine.com/Userjyuchu?jyuchu_denpyo_no='.$call['Memocaller__orderId'],
                                                [
                                                    'target' => '_blank',
                                                    'style' => 'color:#49CFAC'
                                                ]
                                            );
                                        }    
                                        ?>
                                        <p class="media">
                                            <span class="media-left">
                                            <?= ($call['Memocaller__record']!='')?
                                                '<i class="fa fa-play-circle-o record-icon" onclick="playRec(\''.$call['Memocaller__record'].'\')" title="Record"></i>':
                                                ''
                                            ?>
                                            </span>
                                            <span class="media-body">
                                                
                                                <?= $this->Form->textarea(
                                                    'memo',
                                                    [
                                                        'id' => 'text_memo_'.$call['Memocaller__callId'],
                                                        /*'onchange' => 'memo_onchange("'.$call['Memocaller__callId'].'")',*/
                                                        'value' => ($call['Memocaller__memo']!='')?$call['Memocaller__memo']:'',
                                                        'rows' => 1,
                                                        'placeholder' => 'Please note at here.',
                                                        'onkeyup'=>"adjustHeight(this)",
                                                        'onfocus'=> 'showButtonBox("'.$call['Memocaller__callId'].'")'
                                                    ]
                                                ) 
                                                ?>
                                            </span>
                                        </p>
                                    </p>
                                </div>

                                <div id='<?= $call['Memocaller__callId']?>' class="button-box ">
                                    <button type='submit' class='save-but'>Save</button>
                                    <button type="reset" onclick='resetButBox("<?=$call['Memocaller__callId']?>")' class='cancel-but'>Cancel</button>
                                </div>
                                <?= $this->Form->end()?>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
</div>
<div class='audio-rec'>
    <div>
        <?=$this->Html->image('close.png', ['alt' => 'close','title' => 'close', 'onclick'=>'closeRecBox()'] );?>
    </div>
    <div id='audio-play-box'>
        <audio id='play-record' controls>
            <source  src="" type="audio/ogg">
            Your browser does not support the audio element.
        </audio>
    </div>
 
</div>

<script type="text/javascript">
    function playRec(link){
        var arrlink=link.split('||');
        var contenPlayBox='';
        if(arrlink.length>1){
            for(var i=arrlink.length-1;i>=0;i--){
                contenPlayBox+=' <audio id="play-record" controls><source  src="http://api.twilio.com'+arrlink[i]+'" type="audio/ogg">Your browser does not support the audio element. </audio><br>';
            }
            $('#audio-play-box').html(contenPlayBox);
            $(".audio-rec").css("display", "block");
        }else{
            contenPlayBox=' <audio id="play-record" controls><source  src="http://api.twilio.com'+link+'" type="audio/ogg">Your browser does not support the audio element. </audio><br>';
            $('#audio-play-box').html(contenPlayBox);
            $(".audio-rec").css("display", "block");
        }
       
    }  

    function closeRecBox(){
        $(".audio-rec").css("display", "none");
        var audios = document.getElementsByTagName('audio');
        for(var i = 0, len = audios.length; i < len;i++){
                audios[i].pause();
        }
        
    }
    function memo_onchange(call) {
        var memo=$("#text_memo_"+call).val();
        var accountSid='<?php echo $user->accountSid?>';
        var data = { 'callid': call , 'memo': memo,'accountSid':accountSid};
        $.ajax({
            type:"POST",
           	cache:false,
            url:'/call/User/saveMemo',
            data :data,
            success: function (data) { 
            	             
        	}
        });
    }
    function saveAnswer(call,mailName,img,accountSid){
        var data = { 'callid': call, 'mailName': mailName,'img': img,'accountSid' : accountSid};
        $.ajax({
            type:"POST",
            cache:false,
            url:'/call/User/saveAnswer',
            data :data,
            success: function (data) { 
                           
          }
        });
    }
</script>
<script type="text/javascript">
    var graphIn = Morris.Donut({
        element: 'graph-in',
        data: [
            {label: '応答', value: '<?php echo $countCallStatus['ans-completed'];?>' },
            {label: 'キャンセル', value: '<?php echo $countCallStatus['ans-no-answer'];?>' },
            {label: 'ビジー', value: '<?php echo $countCallStatus['ans-busy'];?>' }
        ],
        labelColor: '#16181b',
        formatter: function (y) { return y + '件'; },
        colors: [ '#48CFAD', '#ee502a', '#ffe062' ],
        resize: true
    });
    var graphOut = Morris.Donut({
        element: 'graph-out',
        data: [
            {label: '応答', value: '<?php echo $countCallStatus['call-completed'];?>' },
            {label: 'キャンセル', value: '<?php echo $countCallStatus['call-no-answer'];?>' },
            {label: 'ビジー', value: '<?php echo $countCallStatus['call-busy'];?>' }
        ],
        labelColor: '#16181b',
        formatter: function (y) { return y + '件'; },
        colors: [ '#48CFAD', '#ee502a', '#ffe062' ],
        resize: true
    });
</script>
<script type="text/javascript">
    $('._set-date').click(function(){
        var from = new Date();
        $('#dateOut')[0].value = dateformat(from);
        var type = $(this).attr('type');
        switch(type) {
            case 'week':
                from.setDate(from.getDate()-from.getDay()+1);
                break;
            case 'month':
                from.setDate(1);
                break;
            default:
                break;
        }
        $('#dateIn')[0].value = dateformat(from);
        $($('#filter-form')[0]).trigger('submit');
    })

    function adjustHeight(el){
        el.style.height = (el.scrollHeight > el.clientHeight) ? (el.scrollHeight)+"px" : "60px";
    }

    $( document ).ready(function() {
        $(function () {
            $("textarea").each(function () {
                this.style.height = (this.scrollHeight)+'px';
            });
        });
    });
   
   //full text area
    function adjustHeight(el){
        el.style.height =  '0px';
        el.style.height = (el.scrollHeight)+"px" ;
    }

    $( document ).ready(function() {
        $(function () {
            $("textarea").each(function () {
                this.style.height = (this.scrollHeight)+'px';
            });
        });
    });

    function full(){
        $('#phone-key').css('display','none');
        $('#call-log-data').addClass('width-100');
        $('#full-screen-image').css('display','none');
        $('#exit-full-screen-image').css('display','inline-block');
    }
    function exitFull(){
        $('#phone-key').css('display','block');
        $('#call-log-data').removeClass('width-100');
        $('#full-screen-image').css('display','inline-block');
        $('#exit-full-screen-image').css('display','none');
    }

    function showButtonBox(id){
        $('#'+id).css('display','block');
    }function resetButBox(id){
        $('#'+id).css('display','none');
    }
</script>
