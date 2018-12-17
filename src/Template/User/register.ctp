<?php
    $this->assign('title', 'Register');
    echo $this->Html->charset();
    echo $this->Html->script('jquery.ripples');
    echo $this->Html->css('login');
 
?>
<?php echo $this->Flash->render(); ?>
<!-- <div class="logo"></div> -->
<div class="login-block Register-block">
    <h1>Register</h1>

    <?php 
    	echo $this->Form->create('reg', array('url'=>array('action'=>'/register'), 'id'=>'register-form')); 
    	echo $this->Form->input('email', array('placeholder' => 'メール','label'=>false, 'autocomplete'=>'off','id'=>'register-email'));
    	echo $this->Form->input('name', array('placeholder' => '名前','label'=>false, 'id'=>'name','autocomplete'=>'off'));
    	echo $this->Form->input('company', array('placeholder' => '会社','label'=>false, 'id'=>'company','autocomplete'=>'off'));
    	echo $this->Form->input('pass', array('placeholder' => 'パスワード','id'=>'register-pass','label'=>false, 'autocomplete'=>'off','type'=>'password'));
    	echo $this->Form->input('confirm', array('placeholder' => 'パスワード確認','label'=>false, 'id'=>'confirm','autocomplete'=>'off','type'=>'password'));
    	echo $this->Form->input('提出', array('id'=>'log-but', 'class'=>'submit-button','type'=>'submit'));
    	echo $this->Form->end();    	
    ?>
   
</div>

<script type="text/javascript">
$(document).ready(function() {
 
        $("#register-form").validate({
            rules: {
                email:{
                	required: true,
      				email: true
                },
                pass:{
                	required:true,
                	minlength: 8
                } ,
                company:{
                	required:true,
                } ,
                confirm:{
                	required:true,
                	minlength: 8,
                	equalTo: '#register-pass',
                },
                name:{
                	required:true,
                } 
                
            },

            messages:{
            	confirm:{
            		equalTo:'パスワードとパスワードの確認は同じではありません',
            	}
            	
            }
            
        });


        try {
            $('body').ripples({
                resolution: 512,
                dropRadius: 20, //px
                perturbance: 0.04,
            });
        }
        catch (e) {
            $('.error').show().text(e);
        }

        $('.js-ripples-disable').on('click', function() {
            $('body, main').ripples('destroy');
            $(this).hide();
        });

        $('.js-ripples-play').on('click', function() {
            $('body, main').ripples('play');
        });

        $('.js-ripples-pause').on('click', function() {
            $('body, main').ripples('pause');
        });

        // Automatic drops
        setInterval(function() {
            var $el = $('main');
            var x = Math.random() * $el.outerWidth();
            var y = Math.random() * $el.outerHeight();
            var dropRadius = 20;
            var strength = 0.04 + Math.random() * 0.04;

            $el.ripples('drop', x, y, dropRadius, strength);
        }, 400);
});

</script>