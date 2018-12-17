<?php
    $this->assign('title', 'NTG Center');
    echo $this->Html->charset();
    echo $this->Html->script('jquery.ripples');
    echo $this->Html->css('mainpage');
 
?>

<div class='content'>
	<div class='menu'>
		<ul>
			<li><a class="active" href="">運営会社</a></li>
			<li><a href="">サービス内容</a></li>
			<li><a href="">お問合せ</a></li>
			<li><a href="/user/index">ログイン</a></li>
		</ul>
	</div>	
	<h1 class="sologan">Let Us Make Your Work Easier</h1>
	<p class="p-sologan">NTG center provides communicate solution for your business</p>
	<div class=" center-button">
		<a class="free-but" href="/user/register">無料トライアル</a>
		<a   class="read-more" href="">続きをみる</a>
	</div>
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
