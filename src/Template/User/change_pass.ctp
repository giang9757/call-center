<?php
    $this->assign('title', 'Change password');
    echo $this->Html->charset();
?>
<?php echo $this->Flash->render() ?>
<div class="user form large-9 medium-8 columns content width-full">
    <?= $this->Form->create('log', array('url'=>array('action'=>'/changePass'), 'id'=>'user-change-pass')); ?>
    <fieldset>
        <legend><?= __('Change password') ?></legend>
        <?php
            echo $this->Form->input('oldPassword',['type'=>'password','required'=>true,'label'=>'現在のパスワード']);
	    	echo $this->Form->input('newPassword',['type'=>'password','required'=>true,'label'=>'新しいパスワード']);
	    	echo $this->Form->input('confirmPassword',['type'=>'password','required'=>true,'label'=>'確認のためにもう一度入力してください']);       
        ?>
    </fieldset>
    <?= $this->Form->button(__('保存'),['id'=>'user-change-button']) ?>
    <?= $this->Form->end() ?>
</div>	

<!-- password validate -->

<script type="text/javascript">
	$(document).ready(function() {
	 
	    $("#user-change-pass").validate({
	        rules: {
	            oldPassword:{
	            	minlength: 8
	            },
	            newPassword:{
	            	minlength: 8
	            },
	            confirmPassword:{
	            	minlength: 8,
	            	equalTo: '#newpassword',
	            },
	    	},
	        
		});

	});

</script>