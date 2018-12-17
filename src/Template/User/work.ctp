<?php

  $this->assign('title', 'Call page');
  echo $this->Html->charset();  
  echo $this->Html->script('twilio.min');
  //echo $this->Html->script('jquery.min');
  echo $this->Html->css('callcenter');
?>
<div class="content-bar">
    <!-- <h2><span>CALL PAGE</span></h2> -->
</div>
<div class="content">
    <div class="row">
        <div class="col-lg-4 col-md-6" id='phone-key'>
            <div class="panel" id='key-box'>
                <div class="panel-heading">
                    <div class="panel-control pull-right">
                        <?=$this->Html->tag('p','Loading pigeons...',['id'=>'log']);?>
                    </div>
                    <h3 class="panel-title">
                        <big>
                            <i class="fa fa-phone"></i>&nbsp;
                            <span class="upcase-text">Key Board</span>
                        </big>
                    </h3>
                </div>
                <div class="panel-body">
                    <?= $this->Form->number(
                        'number',
                        [
                            'id' => 'number',
                            'class' => 'number',
                            'placeholder' => 'Enter a phone number to call'
                        ]
                    )
                    ?>
                    <div class="keyboard">
                        <ul>
                            <li>
                                <a href="javascript:void(0)" class="_phone-number"><p>1</p></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="_phone-number"><p>2</p></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="_phone-number"><p>3</p></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="_phone-number"><p>4</p></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="_phone-number"><p>5</p></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="_phone-number"><p>6</p></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="_phone-number"><p>7</p></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="_phone-number"><p>8</p></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="_phone-number"><p>9</p></a>
                            </li>
                            <li id="mute">
                                <a href="javascript:void(0)"  title='保留' class="_phone-clear"><p><i class="fa  fa-lock"></i></p></a>
                            </li>
                            <li id="unmute">
                                <a href="javascript:void(0)"  title='再開' class="_phone-unmute"><p><i class="fa fa-unlock-alt "></i></p></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="_phone-number"><p>0</p></a>
                            </li>
                            <li id='unmute-button'>
                                <a href="javascript:void(0)"  class="unmute-but"><p><i class="fa fa-microphone"></i></p></a>
                            </li>
                            <li id='mute-button'>
                                <a href="javascript:void(0)"  class="mute-but"><p><i class="fa fa-microphone-slash"></i></p></a>
                            </li>
                        </ul>
                    </div>
                    <div class="phone-action">
                        <?php
                            echo $this->Form->button(
                                '',
                                [
                                    'class'=>'call',
                                    'onclick'=>'call();'
                                ]); 
                            echo $this->Form->button(
                                '',
                                [
                                    'class'=>'accept-button',
                                    'onclick'=>'accept();'
                                ]); 
                            echo $this->Form->button(
                                '',
                                [
                                    'class'=>'hangup',
                                    'onclick'=>'hangup();'
                                ]); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-6" id='call-log-data'>
        <div class="panel" data-fill-color="true">
            <div class="panel-heading clear">
                <h3 class="panel-title float-left">
                    <big>
                        <i class="fa fa-list-ul"></i>&nbsp;
                        <span class="upcase-text">call log</span>
                    </big>
                </h3>
                <?= $this->Html->image('full-screen.jpg',['title'=>'full icon','id'=>'full-screen-image','onclick' => 'full()'])?>
                <?= $this->Html->image('2815-200.png',['title'=>'full icon','id'=>'exit-full-screen-image','onclick' => 'exitFull()'])?>
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
                                echo $this->Form->input('returnFlag', array('type'=>'hidden','value'=> 0));
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
                                        <?php
                                            $newNumFrom=str_replace('+81', '0', $call['Memocaller__callFrom']);
                                            if($call['Memocaller__callFrom']!=$user->callerId){
                                        ?>
                                        <a class='p_call' href='/memocaller/showCallLogByPeople/<?=$newNumFrom?>'>
                                            <?php
                                                if($call['Memocaller__image']!=''){
                                                    echo $call['Memocaller__image'];
                                                }else{             
                                                    echo $newNumFrom;
                                                }
                                                 
                                            ?>
                                        </a>
                                        <?php }else{?>
                                        <span>
                                            <?php
                                                if($call['Memocaller__image']!=''){
                                                    echo $call['Memocaller__image'];
                                                }else{             
                                                    echo $newNumFrom;
                                                }
                                                 
                                            ?>
                                        </span>
                                        <?php }?>
                                        <!-- <i class="fa fa-angle-right"></i> -->
                                        <?= $this->Html->image('simple-arrow-hi.png', ['alt' => 'call arrow', 'class'=>'call-arrow']);?>
                                        <?php                                             
                                            echo str_replace('+81', '0', $call['Memocaller__callTo']);
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

<!-- play waiting music when click hold button -->
 <!-- <audio style='display: none' id='waiting-music' controls>
      <source  src="/audio/waiting.mp3" type="audio/ogg">
    Your browser does not support the audio element.
</audio>
 -->
<div class='audio-rec'>
  <div>
    <?=$this->Html->image('close.png', ['alt' => 'close','title' => 'close', 'onclick'=>'closeRecBox()'] );?>
  </div>
  <div id='audio-play-box'>
        
  </div>
 
</div>

<script type="text/javascript">
  var conne; 
  var ord=0; 
  var guestName=''; 
  var holdFlag=0; 
  var callFlag=0; 
  
  Twilio.Device.setup("<?php echo $token; ?>");

    Twilio.Device.ready(function (device) {
      $("#log").text("<?php echo $clientName ?> is ready");
    });

    Twilio.Device.error(function (error) {
        $("#log").text("Error: " + error.message);
        if(error.code=='31205'){       
        	window.location.href = "/user/work";     
      	}
    });

    Twilio.Device.connect(function (conn) {
        conne=conn;
        $("#log").text("Successfully established call");

    });

    Twilio.Device.disconnect(function (conn) {  
        $(".accept-button").css("display", "none");
        $(".call").css("display", "inline-block");
        $('#mute-button').css('display','inline-block');
        $('#unmute-button').css('display','none');
        if(holdFlag==0){
            $("#log").text("Call ended");
            $('#unmute').css('display','none');
            $('#mute').css('display','inline-block');
            $("#key-box").removeClass("anima-header");
            autoGetCallLog();
            //$("#waiting-music")[0].pause();
        }else{
            $("#log").text("The guest is waiting");
            //document.getElementById("waiting-music").play();
        }        

        if(callFlag==1){
            var callid=conne.parameters.CallSid;        
            var from_name='<?php echo $user->name?>';
            var from_email='<?php echo $user->email?>';
            saveCaller(callid,from_name,from_email);
            callFlag==0; 
        }

    });

    Twilio.Device.cancel((conn) => {
      $(".accept-button").css("display", "none");
      $(".call").css("display", "inline-block");
      $("#log").text("Call missed");
      $(".hangup").css("display", "inline-block");
      $("#key-box").removeClass("anima-header");
      autoGetCallLog();
  });

    Twilio.Device.incoming(function (conn) {
      conne=conn;
      var name= conn.parameters.From.replace("+81", '0');
      $("#key-box").addClass("anima-header");
      $(".accept-button").css("display", "inline-block");
      $(".call").css("display", "none");
      $( ".accept-button" ).addClass( "anima" );
      var data = {"num": name};
      $.ajax({
            type:"POST",
            cache:false,
            url:'/User/showGuest',
            data :data,
            success: function (res) { 
              if(res!=0){
                res=JSON.parse(res); 
                $("#log").html("Call from: " + res.Guest_list__name +'  Order: <a target="blank" style="color:black; font-weight:bold; text-decoration: underline;" href="https://ne06.next-engine.com/Userjyuchu?jyuchu_denpyo_no='+res.Guest_list__orderId +'">'+ res.Guest_list__orderId+'</a>');  
                guestName= res.Guest_list__name; 
                ord= res.Guest_list__orderId; 
              }else{
                $("#log").text("Incoming from: " + name);
              }

          }
      });

    });

    function call(num=null) {
        
        if(num==null){
          params = {"PhoneNumber": $("#number").val(),"flag":1};
          Twilio.Device.connect(params);
        }else{
          if(confirm('Are you want to call this number: '+num　+　"?")){
            params = {"PhoneNumber": num,"flag":1};
            Twilio.Device.connect(params);
          }
        }
        callFlag=1;         
    }

    function hangup() {
        Twilio.Device.disconnectAll();
        autoGetCallLog();
       
    }

    function accept() {
        conne.accept();
        $(".hangup").css("display", "inline-block");
        var callid=conne.parameters.CallSid;        
        var mailName='<?php echo $user->name?>';
        var answerEmail='<?php echo $user->email?>';
        var img='<?php echo $user->image?>';
        var accountSid='<?php echo $user->accountSid?>';
        saveAnswer(callid,mailName,img,accountSid,answerEmail );
    }

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
    
    function saveAnswer(call,mailName,img,accountSid,answerEmail){
        var data = { 'callid': call, 'mailName': mailName,'img': img,'accountSid' : accountSid, 'guest':guestName, 'ord':ord,'answerEmail':answerEmail};
        $.ajax({
            type:"POST",
            cache:false,
            url:'/User/saveAnswer',
            data :data,
            success: function (data) { 
                         
            }
        });
    }

    function saveCaller(callid,from_name,from_email){
        var data = { 'callid': callid, 'from_name': from_name,'from_email': from_email};
        $.ajax({
            type:"POST",
            cache:false,
            url:'/User/saveCaller',
            data :data,
            success: function (data) { 
                         
            }
        });
    }

    function autoGetCallLog(){       
        $.ajax({
            type:"GET",
            url:'/User/autoGetCallLogData',          
            success: function (data) {
                data=JSON.parse(data); 
                updateCallLog(data.callId,data.date,data.orderId,data.callFrom,data.image,data.accountSid,data.callTo,data.call_to_name,data.duration,data.status,data.record);                      
            }
        });
    }
    
    var phoneInput = $('#number')[0];
    $('.keyboard a').click(function(){
        switch(this.className) {
            case '_phone-number':
                var number = this.querySelector('p').innerHTML;
                phoneInput.value = phoneInput.value+''+number;
                break;
            case '_phone-unmute':
                holdFlag=0;
                //$("#waiting-music")[0].pause();
                $('#unmute').css('display','none');
                $('#mute').css('display','inline-block');
                var params = {"roomId": conne.parameters.CallSid};
                Twilio.Device.connect(params);
                break;
            case '_phone-clear':
                holdFlag=1;
                $('#unmute').css('display','inline-block');
                $('#mute').css('display','none');
                //$("#waiting-music")[0].play();
                //phát nhạc khi tạm dừng
                var data = { 'callid': conne.parameters.CallSid};
                $.ajax({
                    type:"POST",
                    cache:false,
                    url:'/services/changeCallState',
                    data:data,
                    success: function (data) { 
                                   
                  }
                });

                break;
            case 'mute-but':
                conne.mute(true);
                $('#mute-button').css('display','none');
                $('#unmute-button').css('display','inline-block');
                break;
            case 'unmute-but':            
                conne.mute(false);
                $('#mute-button').css('display','inline-block');
                $('#unmute-button').css('display','none');
                break;
            default: break; 
        }
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
    }
    function resetButBox(id){
        $('#'+id).css('display','none');
    }

    function updateCallLog(callId,date,orderId,callFrom,image,accountSid,callTo,callToName,duration,status,record){ 
          
        if( $('#'+callId).length && record!='') {
            
                $('.newest-'+callId).html('<i class="fa fa-play-circle record-icon" onclick="playRec(&quot;'+record+'&quot;)" title="Record"></i>');
            
        }else{
            
            var newContent='<li class="media li-callog">' ;       
            newContent+='<form method="post" accept-charset="utf-8" action="/user/save-memo">' ;       
            newContent+='<input type="hidden" name="accountSid" id="accountsid" value="'+accountSid+'">' ;       
            newContent+='<input type="hidden" name="callid" id="callid" value="'+callId+'">' ;       
            newContent+='<input type="hidden" name="returnFlag" id="returnFlag" value="0">' ;       
            newContent+='<div class="media-left"><img src="/img/'+status+'.png" alt="'+status+'" title="'+status+ '" class="status-icon"> </div>' ;       
            newContent+='<div class="media-body">' ;       
            newContent+='<p class="pull-right"><small>' ; 
            if(duration>0){
                newContent+=date+'<span class="flag _red">';
            }else{
                newContent+=date+'<span class="flag _gray">';
            }      
            
            if(duration>60){                               
                var s=duration%60;
                var p=(duration-s)/60 ;
                duration=p+'分'+s;
            }
            newContent+=duration+'秒</span></small></p>';       
            newContent+='<p class="media-heading">';  
            // check callerID and show call from
            var newCallFrom=callFrom.replace("+81", "0");
            if(callFrom=='<?=$user->callerId ?>'){
                newContent+='<span>';
            }else{
                newContent+='<a href="/memocaller/showCallLogByPeople/'+newCallFrom+'">';
            }
            if(image!=''){
               newContent+=image;
            }else{               
                newContent+=newCallFrom;
            }  

            if(callFrom=='<?=$user->callerId ?>'){
                newContent+='</span>';
            }else{
                newContent+='</a>';
            }
            newContent+='<img src="/img/simple-arrow-hi.png" alt="call arrow" class="call-arrow">'; 

            // check callerID and show call to
            var newCallTo=callTo.replace("+81", "0");    
            if(callTo=='<?=$user->callerId ?>'){
                newContent+='<span>';
            }else{
                newContent+='<a href="/memocaller/showCallLogByPeople/'+newCallTo+'">';
            }
            if(callToName!=''){
               newContent+=callToName;
            }else{               
                newContent+=newCallTo;
            }  

            if(callFrom=='<?=$user->callerId ?>'){
                newContent+='</span>';
            }else{
                newContent+='</a>';
            }
            //end  call to
            newContent+='</p><p class="help-block">';
            if(orderId!=0){
                newContent+='伝票番号: '+'<a href="https://ne06.next-engine.com/Userjyuchu?jyuchu_denpyo_no='+orderId+'" target="_blank" style="color:#49CFAC">'+orderId+'</a>';
            }    
            newContent+='<p class="media"> <span class="media-left newest-'+callId+'">';
            if(record!=''){
                newContent+='<i class="fa fa-play-circle record-icon" onclick="playRec(&quot;'+record+'&quot;)" title="Record"></i>';
            }
            newContent+='</span> <span class="media-body">';
            newContent+='<textarea name="memo" id="text_memo_'+callId+'" rows="1" placeholder="Please note at here." onkeyup="adjustHeight(this)" onfocus="showButtonBox(&quot;'+callId+'&quot;)"></textarea>';
            newContent+='</span></p></p> </div>';
            newContent+=' <div id="'+callId+'" class="button-box "><button style="margin-right:4px;" type="submit" class="save-but">Save</button><button type="reset" onclick="resetButBox(&quot;'+callId+'&quot;)" class="cancel-but">Cancel</button></div>';
            newContent+='</form></li>';
            newContent+=$('#call-list').html();

            $('#call-list').html(newContent);
        }                 
        
    }
</script>