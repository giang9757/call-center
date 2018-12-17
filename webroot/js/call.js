
  var conne;  
  var token=window.app.token;
  Twilio.Device.setup(token);

    Twilio.Device.ready(function (device) {
      $("#log").text("<?php echo $clientName ?> is ready");
    });

    Twilio.Device.error(function (error) {
      $("#log").text("Error: " + error.message);
      if(error.code=='31205'){       
            location.reload();            
      }
    });

    Twilio.Device.connect(function (conn) {
      $("#log").text("Successfully established call");
    });

    Twilio.Device.disconnect(function (conn) {
      $("#log").text("Call ended");
      $(".accept-button").css("display", "none");
      $(".call").css("display", "inline-block");
    });

    Twilio.Device.cancel((conn) => {
        $(".accept-button").css("display", "none");
      $(".call").css("display", "inline-block");
      $("#log").text("Call missed");
      $(".hangup").css("display", "inline-block");
  });

    Twilio.Device.incoming(function (conn) {
      conne=conn;
      var name= conn.parameters.From.replace("client:", '');
      $("#log").text("Incoming connection from: " + name);
      $(".hangup").css("display", "none");
      $(".accept-button").css("display", "inline-block");
      $(".call").css("display", "none");
      $( ".accept-button" ).addClass( "anima" );
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
    }

    function hangup() {
      Twilio.Device.disconnectAll();
      $(".accept-button").css("display", "none");
      $(".call").css("display", "inline-block");
    }

    function accept() {
        conne.accept();
        $(".hangup").css("display", "inline-block");
        var callid=conne.parameters.CallSid;        
        var mailName='<?php echo $user->email."-".$user->name?>';
        var img='<?php echo $user->image?>';
        var accountSid='<?php echo $user->accountSid?>';
        saveAnswer(callid,mailName,img,accountSid);
    }

    function playRec(link){
      link='http://api.twilio.com'+link;
      $("#play-record").attr("src", link);
      $(".audio-rec").css("display", "block");
    }  

    function closeRecBox(){
      $(".audio-rec").css("display", "none");
      $("#play-record")[0].pause();
        
    }

    function memo_onchange(call) {
        var memo=$("#text_memo_"+call).val();
        var accountSid='<?php echo $user->accountSid?>';
        var data = { 'callid': call , 'memo': memo,'accountSid':accountSid};
        $.ajax({
            type:"POST",
            cache:false,
            url:'/User/saveMemo',
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
            url:'/User/saveAnswer',
            data :data,
            success: function (data) { 
                           
          }
        });
    }
