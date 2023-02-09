$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
});
$(function(){
    var modal = document.getElementById("cm_upload");
    $(document).on('click','#add_cm',function(){
        modal.style.display = "block";
    });
    $(document).on('click','#close',function(){
        modal.style.display = "none";
    });
    window.onclick = function(event){
        if(event.target == modal){
            modal.style.display = "none";
        }
    }
    

    /* cm_upload */
    $('#imagetemp').change(function(){
        readimage(this);
    })
    function readimage(file){
        if(file.files && file.files[0]){
            var reader = new FileReader();
            reader.onload=function(e){
                $('#image_prev').removeAttr('src');
                $('#image_prev').attr('src',e.target.result);
            }
            reader.readAsDataURL(file.files[0]);
        }
    }
    /* article_upload */
    $('#imagetemp_art').change(function(){
        readimage_art(this);
    })
    function readimage_art(file){
        if(file.files && file.files[0]){
            var reader = new FileReader();
            reader.onload=function(e){
                $('#article_img').removeAttr('src');
                $('#article_img').attr('src',e.target.result);
            }
            reader.readAsDataURL(file.files[0]);
        }
    }
    /* /article_upload */
    /* counter */
    const description = document.getElementById("description");
    const number = document.getElementById("number");
    if(description == null){ return }
    description.addEventListener('input',function(){
        const count = description.value.length;
        const left_number = 1000 - count;
        number.textContent = left_number;
        if(left_number>0){
            $('#description').text('');
        }else if(left_number==0){
            $('#description').attr('maxlength','1000');
        }
    })
    /* /counter */
});

/* update quantity */
$(function(){
    $(document).on('click','.minus',function(e){
        e.preventDefault();
        showLoader();
        var a = $(this);
        var input = a.parents('td').children('input').val();
        var link = a.attr('href')+"&quantity="+input;
        $.ajax({
            url:link,
            dataType:"json",
            success:function(data){
                if(data.quantity){
                    a.parents().parents().children('#quantity').html(data.quantity);
                }
                if(data.price){
                    a.parents().parents().children('#price').html(data.price+' $');
                }
                if(data.total){
                    a.parents().parents().children('#total').html(data.total+' $');
                }
                if(data.quantity_error){
                    $('#error').html(data.quantity_error);
                }else{
                    $('#error').html('');
                }
                a.parents('td').children('input').val('');
                hideLoader();
            }
        });
    })

    $(document).on('click','.plus',function(e){
        var stock_height = document.querySelector('.stock').clientHeight;
        var stock_width = document.querySelector('.stock').clientWidth;
        e.preventDefault();
        showLoader();
        var a = $(this);
        var input = a.parents('td').children('input').val();
        var link = a.attr('href')+"&quantity="+input;
        $.ajax({
            url:link,
            dataType:"json",
            success:function(data){
                if(data.quantity){
                    a.parents().parents().children('#quantity').html(data.quantity);
                }
                if(data.price){
                    a.parents().parents().children('#price').html(data.price+'$');
                }
                if(data.total){
                    a.parents().parents().children('#total').html(data.total+'$');
                }
                if(data.quantity_error){
                    $('#error').html(data.quantity_error);
                }else{
                    $('#error').html('');
                }
                a.parents('td').children('input').val('');
                hideLoader();
            }
        })
    });
    
    $(document).on('click','.del',function(e){
        e.preventDefault();
        var a = $(this);
        var url = a.attr('href');
        var cart_error = $('.cart-error');
        cart_error[0].style.display='block';
        $(this).addClass('confirm');
        $('.error-message').html('<p>Supprimer cet article？</p><button><a class="deleteArticle" href="'+url+'">Supprimer</a></button>');
    })
    $(document).on('click','.deleteArticle',function(e){
        e.preventDefault();
        var a = $(this);
        var url = a.attr('href');
        var cart_error = $('.cart-error');
        cart_error[0].style.display='none';
        $.ajax(url,{
            success:function(){
                $('.confirm').parents('tr').fadeOut();
            }
        })
    })
});

$(function(){
    $(document).on('click','.col-sort',function(e){
        e.preventDefault();
        var col_name = $(this).attr('id');
        var order = $(this).data("order");
        var link = $(this).attr("href");
        var arrow = '';
        if(order == 'desc'){
            arrow = '&nbsp;<span class="fas fa-sort-down"></span>';
        }else{
            arrow = '&nbsp;<span class="fas fa-sort-up"></span>';
        }
        $.ajax({
            url:link,
            data:{col_name:col_name,order:order},
            dataType:"json",
            success:function(data){
                $("#st_table").html(data.articles);
                $('#'+col_name+'').append(arrow);
            }
        });
    })
});

/* loader */
function showLoader(){
    const loader = $('.loader');
    loader.addClass('loading');
    if(loader == null){ return }
    loader[0].style.display='block';
    loader.attr('aria-hidden','false');
}(jQuery)
function hideLoader(){
    $('.loader').remove('loading');
    const loader = $('.loader');
    if(loader == null){ return }
    loader.attr('aria-hidden','true');
    loader[0].style.display='none';
}(jQuery)
/* /loader */
$(function(){
    $(document).on('click','.close-error',function(e){
        var cart_error = $('.cart-error');
        var confirm = $('.confirm');
        cart_error[0].style.display='none';
        if(confirm==null){ retun }
        $('.del').removeClass('confirm');
    });
});
/* add_article_cart */
$(function(){
    $(document).on('click','#add',function(e){
        e.preventDefault();
        showLoader();
        var url=$(this).attr('href');
        $.ajax({
            url:url,
            type:'POST',
            dataType:'json',
            success:function(data){
                if(data.success){
                    val1 = $(data.success);
                    $('#in-cart').html(val1);
                    val1.hide().fadeIn();
                }
                if(data.error){
                    error = $(data.error);
                    $('#error-cart').html(error);
                    var cart_error = $('.cart-error');
                    cart_error[0].style.display='block';
                }
                hideLoader();
            }
        })
    });
});
/* /add_article_cart */
$(function(){
    $('#imagetemp_art').change(function(){
        readimage_art(this);
    })
    function readimage_art(file){
        if(file.files && file.files[0]){
            var reader = new FileReader();
            reader.onload=function(e){
                $('#article_img').attr('src',e.target.result);
            }
            reader.readAsDataURL(file.files[0]);
        }
    }
    $('#form_description').on('submit',function(event){
        event.preventDefault();
        showLoader();
        text = document.getElementById("description").value;
        desc = document.getElementById("desc");
        text = text.replace(/   /g,"[sp][sp]");
        text = text.replace(/\n/g,"[nl]");
        desc.value=text;
        $.ajax({
            url:$(this).attr('action'),
            method:"POST",
            data:$(this).serialize(),
            dataType:"json",
            success:function(data){
                if(data.error){
                    if(data.description_error != ''){
                        $('#description_error').html(data.description_error);
                    }else{
                        $('#description_error').html('');
                    }
                    hideLoader();
                }
                if(data.success){
                    $("#description_success").html(data.success);
                    $("#sp_description").html(data.description);
                    $("#description_error").html('');
                    hideLoader();
                }
            }
        });
    });
});


function show(){
    var input = document.getElementById("password");
    var confirm_input = document.getElementById("c_password");
    if(input.type==="password" && confirm_input.type === "password"){
        input.type = "text";
        confirm_input.type = "text";
    }else{
        input.type = "password";
        confirm_input.type = "password";
    }
}
function show_password(){
    var input = document.getElementById("password");
    if(input.type==="password"){
        input.type = "text";
    }else{
        input.type = "password";
    }
}
function drop(){
    var wcolor = document.getElementById("wcolor");
    var check_color = document.getElementById("check_color");
    if(check_color.style.display === "block" && wcolor.disabled === false){
        check_color.style.display="none";
    }else{
        check_color.style.display="block";
    }
}
(function($) {
    $.fn.inputFilter = function(inputFilter) {
      return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else if(this.value!=","){
          this.value = "";
        }
      });
    };
}(jQuery));
function addbreak(){
    price = document.getElementById("art_price");
    desc = document.getElementById("desc");
    text = document.getElementById("description").value;
    new_price = price.value.replaceAll(",",".");    
    text = text.replace(/   /g,"[sp][sp]");
    text = text.replace(/'/g, '&rsquo;');
    text = text.replace(/\n/g,"[nl]");
    document.getElementById("art_price").value = new_price;
    desc.value = text;
}
function breack(){
    text = document.getElementById("description").value;
    desc = document.getElementById("desc");
    text = text.replace(/   /g,"[sp][sp]");
    text = text.replace(/\n/g,"[nl]");
    text = text.replace(/'/g, '&rsquo;');
    desc.value = text;
}

(function($){
    $(document).on('click','.del-v',function(e){
        e.preventDefault();
        showLoader();
        var a = $(this);
        var url = a.attr('href');
        $.ajax(url,{
            success:function(){
                a.parents('tr').fadeOut();
                hideLoader();
            }
        })
    })
    
    $(document).on('click','.col-sort-solded',function(e){
        e.preventDefault();
        var col_name = $(this).attr('id');
        var order = $(this).data("order");
        var link = $(this).attr("href");
        var arrow='';
        if(order == 'desc'){
            arrow = '&nbsp;<span class="fas fa-sort-down"></span>';
        }else{
            arrow = '&nbsp;<span class="fas fa-sort-up"></span>';
        }
        $.ajax({
            url:link,
            method:"POST",
            data:{col_name:col_name,order:order},
            dataType:"json",
            success:function(data){
                $("#sld-table").html(data.articles);
                $('#'+col_name+'').append(arrow);
            }
        });
    })
})(jQuery);
$(function(){
    $(document).on('click','.bar',function(){
        var side_bar = $('.left-sidebar');
        var stock_bar = $('.stock-sidebar');
        var close_nav = $('#close-nav');
        if(side_bar.hasClass('side-show') || stock_bar.hasClass('side-show')){
            side_bar.removeClass('side-show');
            stock_bar.removeClass('side-show');
            close_nav.css({'display':'none'});
        }else{
            side_bar.addClass('side-show');
            stock_bar.addClass('side-show');
            close_nav.css({'display':'block'});
        }
    });
    $('#close-nav').on('click',function(){
        var side_bar = $('.left-sidebar');
        var stock_bar = $('.stock-sidebar');
        side_bar.removeClass('side-show');
        stock_bar.removeClass('side-show');
        $(this).css({'display':'none'});
    });
    
});


