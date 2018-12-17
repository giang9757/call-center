<?php

  $this->assign('title', 'Guest detail');
  echo $this->Html->charset();  
  echo $this->Html->css('callcenter');
?>
<div class="content-bar">
    <h2><span>GUEST DETAIL</span></h2>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-4">
	        <?= $this->Form->create(
	            'log', 
	            [
	                'url'=>
	                [
	                    'action'=>'/saveInfo'
	                ], 
	                'id'=>'change-info'
	            ]
	        )
	        ?>
	        <div class="panel fade in panel-default" data-init-panel="true">
	            <div class="panel-heading">
	                <h3 class="panel-title">
	                	  	<i class="fa fa-user-circle-o"></i>&nbsp;
                            <span >Information</span>
	                </h3>
	            </div>
	            <div class="panel-body">
	                <div class="form-group">
	                    <label class="control-label">Name</label>
	                    <div class="input-group input-group-in">
	                        <span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
	                        <?= $this->Form->text(
	                            'name', 
	                            [
	                                'class' => 'form-control',
	                                'value' =>$info->name
	                            ]
	                        )
	                        ?>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label class="control-label">Phone Number</label>
	                    <div class="input-group input-group-in">
	                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
	                        <?= $this->Form->number(
	                            'phone', 
	                            [
	                                'class' => 'form-control number',
	                                'value' =>$info->num
	                            ]
	                        )
	                        ?>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label class="control-label">Order ID</label>
	                    <div class="input-group input-group-in">
	                        <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
	                        <?= $this->Form->number(
	                            'order', 
	                            [
	                                'class' => 'form-control number',
	                                'value' =>$info->orderId,

	                            ]
	                        )
	                        ?>
	                    </div>
	                </div>
	                <div class="form-group pull-right">
	                    <?= $this->Form->button(
	                        'Save',
	                        [
	                            'id' => 'pass-change-button',
	                            'class' => 'button _big _green'
	                        ]   
	                    ) 
	                    ?>
	                </div>
	            </div>
	        </div>
	        <?= $this->Form->end() ?>
	    </div>



        <div class="col-lg-8 col-md-6" id='call-log-data'>
        <div class="panel" data-fill-color="true">
            <div class="panel-heading clear">
                <h3 class="panel-title float-left">
                    <big>
                        <i class="fa fa-list-ul"></i>&nbsp;
                        <span class="upcase-text">detail</span>
                        
                    </big>
                </h3>
                
            </div>
            <div class="panel-body">
                <div data-toggle="slimScroll">
                    <ul id='call-list' class="media-list">
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
                                echo $this->Form->input('peopleNumber', array('type'=>'hidden','value'=> $info->num));
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
                                        <span >
                                            <?php
                                                if($call['Memocaller__image']!=''){
                                                    echo $call['Memocaller__image'];
                                                }else{
                                                    
                                                    echo str_replace('+81', '0', $call['Memocaller__callFrom']);
                                                }
                                                 
                                            ?>
                                        </span>
                                        <!-- <i class="fa fa-angle-right"></i> -->
                                        <?= $this->Html->image('simple-arrow-hi.png', ['alt' => 'call arrow', 'class'=>'call-arrow']);?>
                                        <?php 
                                            if($call['Memocaller__callTo']==$user->callerId || $call['Memocaller__callTo']==''){
                                                 echo 'Center';
                                            }else{
                                                if($call['Memocaller__call_to_name']!=''){
                                                    echo $call['Memocaller__call_to_name'];
                                                }else{
                                                    echo str_replace('+81', '0', $call['Memocaller__callTo']);
                                                }
                                            }
                                            
                                        ?>
                                    </p>
                                    <p class="help-block">
                                        
                                        <?php
                                        if($call['Memocaller__orderId']!=0){
                                            echo '伝票番号: '.$this->Html->link(
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
                                                '<i class="fa fa-play-circle record-icon" onclick="playRec(\''.$call['Memocaller__record'].'\')" title="Record"></i>':
                                                ''
                                            ?>
                                            </span>
                                            <span class="media-body">
                                                
                                                <?= $this->Form->textarea(
                                                    'memo',
                                                    [
                                                        'id' => 'text_memo_'.$call['Memocaller__callId'],
                                                    
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
                            <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
    
</div>
<div class='audio-rec'>
  <div>
    <?=$this->Html->image('close.png', ['alt' => 'close','title' => 'close', 'onclick'=>'closeRecBox()'] );?>
  </div>
  <div id='audio-play-box'>
        
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

    function showButtonBox(id){
        $('#'+id).css('display','block');
    }
    function resetButBox(id){
        $('#'+id).css('display','none');
    }
</script>