<?php
    $this->assign('title', 'メンバー');
    echo $this->Html->charset();
    echo $this->Html->css('switch');
?>
<?php echo $this->Flash->render() ?>
<div class="user form large-9 medium-8 columns content width-full">
    <?= $this->Form->create('log', array('url'=>array('action'=>'/employee'), 'id'=>'user-add-employee')); ?>
    <fieldset>
        <legend><?= __('メンバー') ?></legend>
        <div id='box-error'></div>
        <?php
            echo $this->Form->input(
            	'メールアドレス',
            	[ 'label'=>false, 'placeholder'=>'メール',
            	'class'=>'add-em',            	
            	'type'=>'text','required' => true,
            	'templates' => ['inputContainer' => '{{content}}'  ],]);
            echo $this->Form->input(
            	'名前',
            	[ 'label'=>false, 'placeholder'=>'名前',
            	'class'=>'add-em',             	
            	'type'=>'text','required' => true,
            	'templates' => ['inputContainer' => '{{content}}'  ],]);
            echo $this->Form->button(__('追加'),['id'=>'employee-add-button']);       
        ?>
    </fieldset>
    <?= $this->Form->end() ?>
    <div class='sync'>
    	<table class='employee-list'>
	    	<tr>
	    		<th class='width-200'>メール</th>
	    		<th class='width-200'>名前</th>    		
	    		<th>確認</th>
	    		<th>画像</th>
	    		<th class='width-17'>アクセス許可</th>
	    	</tr>
	    	<?php
	    	//echo '<pre>';print_r($emp);echo '</pre>';exit;
	    		foreach ($emp as $value) {    		
	    	?>
	    	<tr>
	    		<td><?=$value['User__email']?></td>
	    		<td><?=$value['User__name']?></td>
	    		<td class='padding-25'><?php 

	    			if($value['User__confirm']==1){
	    				echo $this->Html->image('confirmed.png', ['alt' => 'user image', 'title'=>'確認済み']);
	    			}else{
	    				echo $this->Html->image('notconfirmyet.png', ['alt' => 'user image','title'=>'あなたの従業員は使用する前に電子メールでアカウントを確認する必要があります。']);
	    			}
	    		?></td>
	    		<td class='em-img'><?=$this->Html->image('user/'.$value['User__image'], ['alt' => 'user image']);?></td>
	    		<td class='padding-25'>
	    			<label class="switch">
					  <input <?php if($value['User__active']==1){ echo 'checked';}?> onchange="changeActive('<?=$value['User__email']?>')" id='<?php echo str_replace('@', '', $value['User__email'])?>' class='check-active' type="checkbox">
					  <span class="slider round"></span>
					</label>
	    		</td>
	    	</tr>
	    	<?php }?>
	    </table>
    </div>
    
</div>	

<!-- email validate -->

<script type="text/javascript">
	$(document).ready(function() {
	 
	    $("#user-add-employee").validate({
	    	errorPlacement: function(error, element) {
                            if(element.attr("name") == "email"){
                                error.appendTo('#box-error');
                                return;
                            }
                            if(element.attr("name") == "name"){
                                    error.appendTo('#box-error');
                                    return;
                            }else {
                                error.insertAfter(element);
                            }
                       },
               
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
            url:'/call/User/setactive',
            data :data,
            success: function (data) { 
            	             
        	}
        });
	}
</script>