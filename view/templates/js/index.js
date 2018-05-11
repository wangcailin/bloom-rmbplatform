$(function(){
	var scroll = new IScroll('#iscroll');
	var maskScroll = new IScroll('#maskScroll');
    var service;
    var site;
	var emailEx = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;  // 邮箱判断
	var repeatLimit = 5;
	var phoneCheck = [/[^0-9()+-.\s]/, "0{"+repeatLimit+",}|1{"+repeatLimit+",}|2{"+repeatLimit+",}|3{"+repeatLimit+",}|4{"+repeatLimit+",}|5{"+repeatLimit+",}|6{"+repeatLimit+",}|7{"+repeatLimit+",}|8{"+repeatLimit+",}|9{"+repeatLimit+",}", /0123456789/, /1234567890/, /9876543210/, /0987654321/, /123456789/, /123456/, /234567/, /345678/, /456789/, /567890/, /012345/, /098765/, /987654/, /876543/, /765432/, /654321/, /543210/];
    var phoneNumberCheck = function phoneNumberCheck(value){
        var phoneFieldValue = value;
        var isren=true;
        $(phoneCheck).each(function(index, regexCheck){
            var phoneCheckIndex = new RegExp(regexCheck);
            if (phoneCheckIndex.test(phoneFieldValue)) {
                isren=false;
                return false;
            }
            else {
                if(phoneFieldValue.length < 5 || phoneFieldValue.length > 11)
                {
                    isren=false;
                    return false;
                }
                else
                {
                    isren=true;
                    return true;
                }
            }
        });
        return isren;
    }
	$('.schedule').click(function(){
		$('.maskbox').show();
		maskScroll.refresh();
	})
	$('.closeIcon').click(function(){
		$('.maskbox').hide();
	})
	$('.maskOpt .optionbtn').click(function(){
		$('.maskOpt .optionbtn').removeClass('active');
		$(this).addClass('active');
		$('.showBox').hide();
		$('.showBox').eq($(this).index()).show();
	})

    $('.optOne .optionbtn').click(function(){
        $(this).toggleClass('active');
    })

    $('.optTwo .optionbtn').click(function(){
        $(this).toggleClass('active');
    })

	$('.inputBox input').on('input', function(){
		$(this).parents('.inputBox').addClass('active');
		$(this).parents('li').find('.error').hide();
	})

	$('.inputBox input').focus(function(){
		$(this).parents('.inputBox').addClass('active');
	}).blur(function(){
		if($(this).val() == ' ' || $(this).val().length <= 0){
			$(this).parents('.inputBox').removeClass('active');
		}
	})
	var guizZ = function(target, name){
		var istrue = true;
		if(target.val().length === 0 || target.val().length == ''){
			target.parents('li').find('.error').text(name + '不能为空').show();
			istrue = false;
		}
		return istrue;
	}
	var step = false;
    function stepChan(){
        step = false;
    }
	$('.submit').click(function(){
		if (step == false){

            if($('.optOne .active').length == 0){
                $('.optOneerror').show();
                scroll.scrollTo(0,0,0);
                return false;
            }
            if($('.optOne .active').length === 1){
                service = $('.optOne .active').text();
            }else {
                service = $('.optOne .optionbtn').eq(0).text() + ',' + $('.optOne .active').eq(1).text();
            }
            if($('.optTwo .active').length === 1){
                site = $('.optTwo .active').text();
            }else {
                site = $('.optTwo .active').eq(0).text() + ',' + $('.optTwo .active').eq(1).text();
            }

            var object = {
                username: $('#username').val(),
                username_for: $('#username_for').val(),
                company: $('#company').val(),
                position: $('#position').val(),
                province: $('#province').val(),
                country: $('#country').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                service: service,
                site: site
            };
            if (!guizZ($('#username'), '名')) {
                scroll.scrollTo(0,-$('#username').offset().top-200,0);
                return;
            } else if(!guizZ($('#username_for'), '姓')) {
                scroll.scrollTo(0,-$('#username_for').offset().top-200,0);
                return;
            } else if(!guizZ($('#company'), '公司')) {
                scroll.scrollTo(0,-$('#company').offset().top-200,0);
                return;
            } else if(!guizZ($('#position'), '职位')) {
                scroll.scrollTo(0,-$('#position').offset().top-200,0);
                return;
            } else if(!guizZ($('#province'), '城市')) {
                scroll.scrollTo(0,-$('#province').offset().top-200,0);
                return;
            } else if(!guizZ($('#country'), '国家')) {
                scroll.scrollTo(0,-$('#country').offset().top-200,0);
                return;
            }else if(!guizZ($('#email'), '公司邮箱')){
                return;
            }else if(!emailEx.test($('#email').val())){
                $('#email').parents('li').find('.error').text('邮箱格式不正确').show();
                return;
            }else if(!guizZ($('#phone'), '电话')){
                return;
            } else if(!phoneNumberCheck($('#phone').val())){
                $('#phone').parents('li').find('.error').text('电话格式不正确').show();
                return;
            }
            console.log(object);
            // $('.submitSuccess').show();

            step = true;
            $.post('index.php?m=event&a=registerSubmit', object, function(res){
                if (res == '1'){
                    $('.submitSuccess').show();
                }else{
                    alert('提交失败');
                }
                stepChan();
            }, 'json')
		}

	})

	$('.successClose').click(function(){
		$('.submitSuccess').hide();
	})


	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, isPassive() ? {
		capture: false,
		passive: false
	} : false);
	function isPassive() {
	    var supportsPassiveOption = false;
	    try {
	        addEventListener("test", null, Object.defineProperty({}, 'passive', {
	            get: function () {
	                supportsPassiveOption = true;
	            }
	        }));
	    } catch(e) {}
	    return supportsPassiveOption;
	}

})