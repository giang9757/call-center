 <?php
 if($dialCallStatus=='completed'){
?>
	<Response>
	  <Hangup/>
	</Response>
<?php }else{?>
	<Response>
		<?php
			if($busyRec!='' && file_exists(WWW_ROOT."audio/".$busyRec)){

	            echo '<Play>http://u-you.co.jp/call/audio/'.$busyRec.'</Play>';            
	            
	        }else{
	            echo '<Say language="ja-jp">申し訳ありませんが、すべてのエージェントがビジー状態になりました。後で電話してください </Say>';
	        }
		?>
	</Response>
<?php }?>