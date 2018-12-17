$(function(){
	if($(window).width()<650) {
		$(document.body).addClass('_close-side-nav');
	}
	$('.top-nav .btn[data-sidebar=toggleVisible]').click(function(){
		if($(document.body).hasClass('_close-side-nav')) {
			$(document.body).removeClass('_close-side-nav');
		}
		else {
			$(document.body).addClass('_close-side-nav');
		}
	});
	$('.menu-list a[data-sidebar=toggleCollapse]').click(function(){
		if($(document.body).hasClass('_wrap-side-nav')) {
			$(document.body).removeClass('_wrap-side-nav');
			this.querySelector('i').className = 'fa fa-angle-double-left';
		}
		else {
			$(document.body).addClass('_wrap-side-nav');
			this.querySelector('i').className = 'fa fa-angle-double-right';
		}
	});
    $('.number').keydown(function(e){
        var reg = '/[0-9]/';
        if(e.keyCode!=8 && e.keyCode!=13 && e.key !== 'Control' && !e.ctrlKey && isNaN(e.key)) {
            return false;
        }
        return true;
    });
    
    $('[class^="nice"] label').click(function(){
    	$($(this).parent()[0].querySelector('input')).trigger('click');
    })
    
    $('[data-input="daterangepicker"]').each(function(){
        var $this = $(this),
        dataset = $(this).data();
        dataset.locale = { format: 'YYYY/MM/DD' };
        dataset.applyClass = 'btn-primary';
        $this.daterangepicker(dataset);
    });
    $('.upload-file input[type=file]').change(function(){
        var name = this.files.length && 'name' in this.files[0]?this.files[0].name:'';
        $(this).parent()[0].querySelector('span').innerHTML = name;
    });
    $(window).resize(function(){
        var height = $('body').height();
        $('div[data-toggle="slimScroll"]').each(function(){
            $(this).css('max-height', 'calc('+height+'px - 19.5em)');
        })
    });
    $(document).on("hide.bs.dropdown", ".dropdown-ext", function() {
        var a = $(this),
            b = a.children(".dropdown-menu");
    });
    var b = $(".dropdown-ext .dd-body");
    b.length && a.initSlimScroll(b, 360), $(".dropdown-menu-ext .dd-head, .dropdown-menu-ext .dd-actions, .dropdown-menu-ext button").on("click", function(a) {
        a.stopPropagation();
    });
});
$(document).ready(function(){
    $(window).trigger('resize');
});
function dateformat(date) {
    return date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate();
}
$.fn.refresh = function(){
    
}