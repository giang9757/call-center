<?php
    $this->assign('title', 'Confirm');
    echo $this->Html->charset();
    echo $this->Html->script('jquery.ripples');
    echo $this->Html->css('login');
 
?>
<?php echo $this->Flash->render() ?>
<!-- <div class="logo"></div> -->
<div class="login-block">

    <h1>Call center</h1>
    <p class="register">
    あなたの電子メールをアクティブにチェックしてください。<br>
    <span>メールが届かない場合は、</span> 
    <?php echo $this->Html->link( 'こちらをクリックしてください','User/resendConfirm');?><br>
    <?php echo $this->Html->link( 'ログアウト','User/logout');?></p>
    
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