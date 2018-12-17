<?php
if (isset($roomId)) { 
    echo"<Response>
           <Dial  record='true' ><Queue>$roomId</Queue></Dial>
        </Response>";          
}else{
?>


<Response>
	<?php if(strtotime($now)<strtotime($fr)||strtotime($now)>strtotime($to)){
        if($service['timeoutRec']!='' && file_exists(WWW_ROOT."audio/".$service['timeoutRec'])){

            echo '<Play>http://u-you.co.jp/call/audio/'.$service['timeoutRec'].'</Play>';            
            
        }else{
            echo '<Say language="ja-jp">お電話ありがとうございます。申し訳ございませんが、今は時間が経ってから、後でもう一度お試しください</Say>';
        }
		

	}else{
        if($call_flag==0) {
        	if($service['timeinRec']!='' && file_exists(WWW_ROOT."audio/".$service['timeinRec'])){

                echo '<Play>http://u-you.co.jp/call/audio/'.$service['timeinRec'].'</Play>';            
                
            }
        }?>
    <Dial 
        <?php 
        if($call_flag==0) {?> 
            timeout ='10' 
            action="/call/Services/secondForward/<?php echo $callerId?>/<?php echo $service['busyRec']?>"
        <?php }?>    
        record="true" 
        <?php if($numFlag==1){?> 
            callerId="<?php echo $callerId ?>" 
        <?php }?>>
        
        <?php echo $numberOrClient ?>
    </Dial>

    <?php }?>
</Response>

<?php }?>