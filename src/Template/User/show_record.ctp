<?php
	$this->assign('title', 'Record');
	echo $this->Html->charset();	
  //echo $this->Html->script('jquery.min');
	echo $this->Html->script('jquery-ui');
  echo $this->Html->css('callcenter');
	echo $this->Html->css('jquery-ui');
?>

<div class="padding-20">
	<div>
      <form action='/call/User/showRecord' method="Post">
            <input type='text' id='dateIn'  class="datepicker" name='from' placeholder='Time From'/>
            <input type='text' id='dateOut' class="datepicker" name='to' placeholder='Time To'/>
            <?php
              echo $this->Form->button('Search',['type'=>'submit','class'=>'show']); 
            ?>
      </form>		
      
	</div>
	
    <div>
	    <table id="call-log">
          	<tr>
              	<th class='text-center'>Call Id</th>
              	<th class='text-center'>Date Created</th>              	
             	  <th class='text-center'>Price</th>                
             	  <th class='text-center'>Duration</th>  
                <th class='text-center width-60'>Record</th>                
                <th class='text-center width-140'>Action</th>                
                <th class='text-center width-60'>All <input type="checkbox" id='select_all' name="check-del"></th>                
          	</tr>

          	<?php
              echo $this->Form->create('del-all', array('url'=>array('action'=>'/dellAllRecord'), 'id'=>'del-form')); 
              
	            foreach ($recordingArray as $call){
                        
          	?>

          	<tr>
              	<td ><?=$call['CallSid'];?></td>
              	<td class='text-center'><?php echo date('m-d h:i',strtotime($call['DateCreated']));?></td>
                <td class='text-center'> <?=$call['Price'];?> usd</td>
              	<td class='text-center'> <?=$call['Duration'];?> sec</td>
                <td class='text-center'>
                  	<?php                  	             		
  						          echo $this->Html->image(
                          'Graphicloads-100-Flat-Play.ico', 
            							['alt'=>'record icon',
            							'title'=>'Record',
            							'class'=>'record-icon', 
            							'onclick'=>'playRec("'.$call['Uri'].'")',
            						]);              		           
                  	?>        
				        </td>
                <td class='text-center'>
                    <?php
                      echo $this->Html->link(
                          'Delete',
                          '/User/delRecord/'.$call['Sid'],
                          ['class' => 'del']
                      ); 
                    ?>
                </td>
                <td class='text-center'> <?php echo $this->Form->input('',

                  [
                      'type'=>'checkbox', 
                      'class'=>'top-20',
                      'name'=>"del_flag[]",
                      'label'=>false, 
                      'value'=>$call['Sid'],
                      'templates' => 
                          [
                              'inputContainer' => '{{content}}'
                          ],
                      'hiddenField'=>false,
                  ])?>
                </td>
          	</tr>

          	<?php }?> 

        </table>
        <?php
            echo  $this->Form->button('Delete All',['class'=>'del']);
            echo $this->Form->end(); 
        ?>
    </div>
</div>

<div class='audio-rec'>
	<div>
		<?=$this->Html->image('close.png', ['alt' => 'close','title' => 'close', 'onclick'=>'closeRecBox()'] );?>
	</div>
	<audio id='play-record' controls>
	    <source  src="" type="audio/ogg">
	    
		Your browser does not support the audio element.
	</audio>
</div>

<script type="text/javascript">
	
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
    $( function() {
      $( ".datepicker" ).datepicker();
    } );


    $('#select_all').click(function(event) {
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;
        });
    }
    else {
      $(':checkbox').each(function() {
            this.checked = false;
        });
    }
  });
</script>