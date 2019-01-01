function goToTop(){
    $('html,body').animate({
        scrollTop: $("html").offset().top
    }, 300);
}
function pageUrl(){
    return document.URL;
}
function delImgInfo(imgId,inputId,name){
  $(imgId).remove();
  $(inputId).attr({
    type: 'text',
    name: name
});
  $(inputId).removeAttr('accept');
}
function flyToElement(flyer, flyingTo) {
    var $func = $(this);
    var divider = 3;
    var flyerClone = $(flyer).clone();
    $(flyerClone).css({position: 'absolute', top: $(flyer).offset().top + "px", left: $(flyer).offset().left + "px", opacity: 1, 'z-index': 999999});
    $('body').append($(flyerClone));
    var gotoX = $(flyingTo).offset().left + ($(flyingTo).width() / 2) - ($(flyer).width()/divider)/2;
    var gotoY = $(flyingTo).offset().top + ($(flyingTo).height() / 2) - ($(flyer).height()/divider)/2;

    $(flyerClone).animate({
        opacity: 0.4,
        left: gotoX,
        top: gotoY,
        width: $(flyer).width()/divider,
        height: $(flyer).height()/divider
    }, 700,
    function () {
        $(flyingTo).fadeOut('fast', function () {
            $(flyingTo).fadeIn('fast', function () {
                $(flyerClone).fadeOut('fast', function () {
                    $(flyerClone).remove();
                });
            });
        });
    });
}
function changeTotal(value,id){
    var total = 0;
    $.each($('.dataCart'),function(index,value){
        var price = $(this).find('.price').text().replace ( /[^\d.]/g, '' );
        var count = $(this).find('.count').val().replace ( /[^\d.]/g, '' );
        price = parseInt(price) || 0;
        count = parseInt(count) || 0;
        total += ( price * count );
    });
    $("#total").html('Tổng: '+ total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '<sup>đ</sup>');
    $("#price").val(total);
    var listCart = readCookie('cart').split(',');
    listCart = listCart.filter(function(value){ if(value != id){ return value;}});
    for(var i =0 ;i<value;i++){
        listCart.push(id);
    }
    createCookie('cart',listCart);
    $('#totalCart').text(listCart.length);
}
function checkForm(element) {
  var isValid = true;
  $(element+ ' ' + '[type="text"]').each(function() {
    if ( $(this).val() === '' )
        isValid = false;
});
  return isValid;
}
function addCart(id,thisE,totalData = 1){
    var cartCookie = readCookie('cart');
    var listCart = [];
    if(cartCookie){
        listCart = cartCookie.split(',');
    }
    for(var i =0 ;i<totalData;i++){
        listCart.push(id);
    }
    createCookie('cart',listCart);
    var itemImg = $(thisE);
    flyToElement($(itemImg), $('#totalCart'));
    $('#totalCart').text(listCart.length);
    var href = $('base').attr('href') + "gio-hang";
    window.history.pushState("", "", href);
    getAjax(href);
}
function clearItemCart(id){
    var isDelete = confirm("Bạn có muốn xóa sản phẩm này không?");
    if(isDelete){
        var cartCookie = readCookie('cart');
        var listCart = cartCookie.split(',');
        var newListCart = [];
        for(var i = 0; i < listCart.length; i++){
            if(listCart[i] != id){
                newListCart.push(listCart[i]);
            }
        }
        createCookie('cart',newListCart);
        $("#data" + id).remove();
        $('#totalCart').text(newListCart.length);
        var total = 0;
        $.each($('.dataCart'), function(index, value) {
            var price = $(this).find('.price').text().replace(/[^\d.]/g, '');
            var count = $(this).find('.count').val().replace(/[^\d.]/g, '');
            price = parseInt(price) || 0;
            count = parseInt(count) || 0;
            total += (price * count);
        });
        $("#price").val(total);
        $("#total").html('Tổng: ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '<sup>đ</sup>');
        if(newListCart.length == 0){
            alert("Bạn đã xóa hết sản phẩm trong giỏ hàng. Chọn một sản phẩm khác để đặt hàng.");
            createCookie('cart', '');
            var href = $('base').attr('href') + "gio-hang";
            window.history.pushState("", "", href);
            getAjax(href);
        }
    }
}
function deleteCart(){
    var isDelete = confirm("Bạn có muốn xóa giỏ hàng không?");
    if(isDelete){
        createCookie('cart', '');
        alert("Bạn đã xóa giỏ hàng. Chọn sản phẩm khác để đặt hàng");
        $('#totalCart').text("0");
        var href = $('base').attr('href') + "gio-hang";
        window.history.pushState("", "", href);
        getAjax(href);
    }
}
function createCookie(name,value) {
    var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}
function getParam(name, url) {
    if (!url) url = document.URL;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function activeMenu(name){
    var active = $('.navAjax').data('active');
    var e = $('.navAjax').data('e') ;
    $('.navAjax '+ e).removeClass(active);
    $('.navAjax '+e+'[data-name="'+name+'"]').addClass(active);
}
function hrefPost(){
    var href = window.location.origin+window.location.pathname;
    if(window.location.search.length > 0){
        if(!getParam('ajax',document.URL)){
            href +='?'+window.location.search+'&ajax=';
        }
    }else{
        href += '?ajax=';
    }
    return href;
}
function removeURLParameter(url, parameter) {
    var urlparts= url.split('?');   
    if (urlparts.length>=2) {
        var prefix= encodeURIComponent(parameter)+'=';
        var pars= urlparts[1].split(/[&;]/g);
        for (var i= pars.length; i-- > 0;) {
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }
        url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
        return url;
    } else {
        return url;
    }
}
function getAjax(href,his = false){
    var eSidebar = '#sidenav-overlay';
    if ($(eSidebar).length > 0) {
        $(".button-collapse").click();
    }
    $.ajax({
        'url': href,
        cache:false,
    }).done(function( data ) {
        if ($('.navbar-collapse').length && $('.navbar-collapse').hasClass('in')) {
            $('.navbar-collapse').collapse('toggle');
        }
        var infoPage = $($.parseHTML(data)).filter("#infoPage");
        if($(infoPage).data() !== undefined){
            $.each($(infoPage).data(),function(index,value){
                $('meta[name='+index+']').attr('content',$(infoPage).data(index));
                $('meta[property="og:'+index+'"]').attr('content',$(infoPage).data(index));
            });
            if($(infoPage).data('url') !== undefined){
                var url = removeURLParameter($(infoPage).data('url'),'ajax');
                $('link[rel=canonical]').attr('href',url);
            }
        }

        var title =  $(infoPage).data('title');
        var name = $(infoPage).data('name');
        document.title = title;
        activeMenu(name);
        goToTop();
        $('.contentAjax').html(data);
        if(his){
            window.history.pushState("", "", href);
        }
    });
}
$(document).ready(function(){
    window.history.pushState("", "", document.URL);

    $('body').on('click','a[data-name]',function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        getAjax(href,true);
    });
    
    $('body').on('click','a[data-toggle="tab"]',function(e){
        window.history.pushState("", "", document.URL);
    });

    $('body').on('change','select[name=province]',function(e){
        $.ajax({
            type:'GET',
            url:$('base').data('url')+'modules/api.php?do=getListDistrict&province='+$(this).val()
        }).success(function(res){
            $('select[name=district]').html(res);
            if($('base').attr('href') == $('base').data('url') && $('.formSearch.searchAjax').length){
                $('.formSearch.searchAjax').submit();
            }
        })
    });

    $(window).on('popstate', function (e) {
        var state = e.originalEvent.state;
        if (state !== null) {
            getAjax(pageUrl());
        }
    });

    $('body').on('submit','.searchAjax',function(e){
        e.preventDefault();
        var value = $(this).serialize();
        var href = $('base').attr('href')+'tim-kiem?'+value;
        window.history.pushState("", "", href);
        getAjax(href);
    });

    $('body').on('submit','.contactAjax',function(e){
        e.preventDefault();
        var action = $(this).attr('action');
        $(this).find('[type=submit]').prop('disabled', true);
        switch(action) {
            case 'post':
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement();
            }
            break;
            case 'cart':
            $.each($(this).find('.cookie'),function(){
                var nameCookie = 'user_' + $(this).attr('name');
                var value = $(this).val();
                createCookie(nameCookie,value);
            });
            break;
            case 'design':
            var eContent = $('[data-content]').data('content');
            var content = $(eContent).html();
            $('[name=content]').val(content);
            break;
        }
        var formData =  new FormData($(this)[0]);
        $.ajax({
          type: "POST",
          url: $('base').attr('href')+'/modules/action.php?do='+action,
          data: formData,
          processData: false,
          contentType: false,
          beforeSend:function(){
            $('[type=submit]').find('i').attr('class', 'fa fa-spin fa-spinner');
        },
    }).done(function( data ) {
        data = $.parseJSON(data);
        NProgress.done();
        $('[type=submit]').find('i').attr('class', 'fa fa-send');
        $('[type=submit]').removeAttr("disabled");
        switch(action) {
            case 'login':
            if(data.error == 0){
                window.location.reload();
            }else{
                alert(data.text);
            }
            break;
            case 'post':
            if(data.error == 0){
                $('.contactAjax[action=post]').find("input[type=text], textarea").val("");
            }
            break;
            case 'cart':
            if(data.error == 0){
                createCookie('cart','');
                var href = $('base').attr('href') + "trang-chu.html";
                window.history.pushState("", "", href);
                getAjax(href);
            }else{
                alert(data.text);
            }
            break;
            case 'contact':
            if(data.error == 0){
                $('.contactAjax').trigger("reset");
            }
            if($('#recaptcha_reload').length){
                $('#recaptcha_reload').click();
            }
        }
        if(data.text.length){
            alert(data.text);
        }
    });
});
    NProgress.start();
    var activeF = $('.navAjax');
    if(activeF.length){
        var aF = $('html').data('load');
        $('.navAjax '+$(activeF).data('e')+'[data-name='+aF+']').addClass($(activeF).data('active'));
    }
    reloadScript();
});

$( document ).ajaxComplete(function() {
    reloadScript();
    if($('.fb-comments').length > 0) {
        $(".fb-comments").attr('data-href',document.URL)
        FB.XFBML.parse();              
    }
});

$( document ).ajaxSend(function() {
    NProgress.start();
});

function reloadScript(){
    NProgress.done();
    try {
        new WOW().init()
    } catch(e) {
        /*console.log(e);*/
    }
    if($('#infoPage').data('file') == 'home'){
        $(".slideAjax").show();
    }else{
        $(".slideAjax").hide();
    }
}

function logout(){
    if(confirm('Bạn thật sự muốn thoát ?')){
        document.cookie = 'email=; path=/; expires=' + new Date(0).toUTCString();
        document.cookie = 'password=; path=/; expires=' + new Date(0).toUTCString();
        window.location.reload();
    }
}

function getSelect(name,id){
    var gets = $(id+' option:selected').map(function() {
        return this.value;
    }).get().join(',');
    $('input[name="'+name+'"]').attr('value',gets);
}
