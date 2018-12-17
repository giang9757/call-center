<?php
    $this->assign('title', 'Login');
    echo $this->Html->charset();
    echo $this->Html->script('jquery.ripples');
    echo $this->Html->css('login');
 
?>
<?php echo $this->Flash->render() ?>
<!-- <div class="logo"></div> -->
<div class="login-block">
    <h1>コールセンター</h1>

    <?php 
    	echo $this->Form->create('log', array('url'=>array('action'=>'/index'), 'id'=>'login-form')); 
    	echo $this->Form->input('mail', array('placeholder' => 'メール','label'=>false, 'id'=>'email','autocomplete'=>'off'));
    	echo $this->Form->input('password', array('placeholder' => 'パスワード','label'=>false, 'id'=>'password','autocomplete'=>'off'));
    	echo $this->Form->input('ログイン', array('id'=>'log-but', 'class'=>'submit-button','type'=>'submit'));
    	echo $this->Form->end();    	
    ?>

    <p class="register">
        <span>アカウントは持っていますか？</span>  <?php echo $this->Html->link( 'こちら','/User/register');?><br>
        <span>パスワードをお忘れですか？</span>  <?php echo $this->Html->link( 'こちら','/User/forgotPass');?><br>

    </p>
   
   
</div>

<script type="text/javascript">
$(document).ready(function() {
 
    $("#login-form").validate({
        rules: {
            mail:{
            	required: {
                    depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
  				email: true
            },
            password:{
            	required:true,
            	minlength: 8
            } 
            
            
        },
        
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