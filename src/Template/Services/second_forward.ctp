<?php
if($dialCallStatus=='completed'){
?>
	<Response>
	  	<Hangup/>
	</Response>
<?php }else{?>
	<Response>
	  	<Dial record="true" action="/call/Services/missService/<?php echo $busyRec?>" timeout="20" callerId="<?php echo $callerId?>" ><Client>Center_mobile</Client></Dial>
	</Response>
<?php }?>