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
    
    
    var resizeableImage = function(image_target) {
        // Some variable and settings
        var $container,
            orig_src = new Image(),
            image_target = $(image_target).get(0),
            event_state = {},
            constrain = false,
            min_width = 60, // Change as required
            min_height = 60,
            max_width = 1200, // Change as required
            max_height = 900,
            resize_canvas = document.createElement('canvas');
      
        init = function(){
      
          // When resizing, we will always use this copy of the original as the base
          orig_src.src=image_target.src;
          $(".resize-handle").remove();
          // Wrap the image with the container and add resize handles
          $(image_target).wrap('<div class="cm-cart_modal"></div>')
          .before('<span class="resize-handle resize-handle-nw"></span>')
          .before('<span class="resize-handle resize-handle-ne"></span>')
          .after('<span class="resize-handle resize-handle-se"></span>')
          .after('<span class="resize-handle resize-handle-sw"></span>');
          // Assign the container to a variable
          $container =  $(image_target).parent('.cm-cart_modal');
      
          // Add events
          $container.on('mousedown touchstart', '.resize-handle', startResize);
          $container.on('mousedown touchstart', 'img', startMoving);
          
        };
      
        startResize = function(e){
          e.preventDefault();
          e.stopPropagation();
          saveEventState(e);
          $(document).on('mousemove touchmove', resizing);
          $(document).on('mouseup touchend', endResize);
        };
      
        endResize = function(e){
          e.preventDefault();
          $(document).off('mouseup touchend', endResize);
          $(document).off('mousemove touchmove', resizing);
        };
      
        saveEventState = function(e){
          // Save the initial event details and container state
          event_state.container_width = $container.width();
          event_state.container_height = $container.height();
          event_state.container_left = $container.offset().left; 
          event_state.container_top = $container.offset().top;
          event_state.mouse_x = (e.clientX || e.pageX || e.originalEvent.touches[0].clientX) + $(window).scrollLeft(); 
          event_state.mouse_y = (e.clientY || e.pageY || e.originalEvent.touches[0].clientY) + $(window).scrollTop();
          
          // This is a fix for mobile safari
          // For some reason it does not allow a direct copy of the touches property
          if(typeof e.originalEvent.touches !== 'undefined'){
              event_state.touches = [];
              $.each(e.originalEvent.touches, function(i, ob){
                event_state.touches[i] = {};
                event_state.touches[i].clientX = 0+ob.clientX;
                event_state.touches[i].clientY = 0+ob.clientY;
              });
          }
          event_state.evnt = e;
        };
      
        resizing = function(e){
          var mouse={},width,height,left,top,offset=$container.offset();
          mouse.x = (e.clientX || e.pageX || e.originalEvent.touches[0].clientX) + $(window).scrollLeft(); 
          mouse.y = (e.clientY || e.pageY || e.originalEvent.touches[0].clientY) + $(window).scrollTop();
          
          // Position image differently depending on the corner dragged and constraints
          if( $(event_state.evnt.target).hasClass('resize-handle-se') ){
            width = mouse.x - event_state.container_left;
            height = mouse.y  - event_state.container_top;
            left = event_state.container_left;
            top = event_state.container_top;
          } else if($(event_state.evnt.target).hasClass('resize-handle-sw') ){
            width = event_state.container_width - (mouse.x - event_state.container_left);
            height = mouse.y  - event_state.container_top;
            left = mouse.x;
            top = event_state.container_top;
          } else if($(event_state.evnt.target).hasClass('resize-handle-nw') ){
            width = event_state.container_width - (mouse.x - event_state.container_left);
            height = event_state.container_height - (mouse.y - event_state.container_top);
            left = mouse.x;
            top = mouse.y;
            if(constrain || e.shiftKey){
              top = mouse.y - ((width / orig_src.width * orig_src.height) - height);
            }
          } else if($(event_state.evnt.target).hasClass('resize-handle-ne') ){
            width = mouse.x - event_state.container_left;
            height = event_state.container_height - (mouse.y - event_state.container_top);
            left = event_state.container_left;
            top = mouse.y;
            if(constrain || e.shiftKey){
              top = mouse.y - ((width / orig_src.width * orig_src.height) - height);
            }
          }
          
          // Optionally maintain aspect ratio
          if(constrain || e.shiftKey){
            height = width / orig_src.width * orig_src.height;
          }
      
          if(width > min_width && height > min_height && width < max_width && height < max_height){
            // To improve performance you might limit how often resizeImage() is called
            resizeImage(width, height);  
            // Without this Firefox will not re-calculate the the image dimensions until drag end
            $container.offset({'left': left, 'top': top});
          }
        }
      
        resizeImage = function(width, height){
          resize_canvas.width = width;
          resize_canvas.height = height;
          resize_canvas.getContext('2d').drawImage(orig_src, 0, 0, width, height);   
          $(image_target).attr('src', resize_canvas.toDataURL("image/png"));  
        };
      
        startMoving = function(e){
          e.preventDefault();
          e.stopPropagation();
          saveEventState(e);
          $(document).on('mousemove touchmove', moving);
          $(document).on('mouseup touchend', endMoving);
        };
      
        endMoving = function(e){
          e.preventDefault();
          $(document).off('mouseup touchend', endMoving);
          $(document).off('mousemove touchmove', moving);
        };
      
        moving = function(e){
          var  mouse={}, touches;
          e.preventDefault();
          e.stopPropagation();
          
          touches = e.originalEvent.touches;
          
          mouse.x = (e.clientX || e.pageX || touches[0].clientX) + $(window).scrollLeft(); 
          mouse.y = (e.clientY || e.pageY || touches[0].clientY) + $(window).scrollTop();
          $container.offset({
            'left': mouse.x - ( event_state.mouse_x - event_state.container_left ),
            'top': mouse.y - ( event_state.mouse_y - event_state.container_top ) 
          });
          // Watch for pinch zoom gesture while moving
          if(event_state.touches && event_state.touches.length > 1 && touches.length > 1){
            var width = event_state.container_width, height = event_state.container_height;
            var a = event_state.touches[0].clientX - event_state.touches[1].clientX;
            a = a * a; 
            var b = event_state.touches[0].clientY - event_state.touches[1].clientY;
            b = b * b; 
            var dist1 = Math.sqrt( a + b );
            
            a = e.originalEvent.touches[0].clientX - touches[1].clientX;
            a = a * a; 
            b = e.originalEvent.touches[0].clientY - touches[1].clientY;
            b = b * b; 
            var dist2 = Math.sqrt( a + b );
      
            var ratio = dist2 /dist1;
      
            width = width * ratio;
            height = height * ratio;
            // To improve performance you might limit how often resizeImage() is called
            resizeImage(width, height);
          }
        };
        $('#js-crop').on('click', function(e){
            e.preventDefault();
            var title = $('#cm_title').val();
            var crop_canvas,
                left = $('.overlay').offset().left - $container.offset().left,
                top =  $('.overlay').offset().top - $container.offset().top,
                width = $('.overlay').width(),
                height = $('.overlay').height();
                crop_canvas = document.createElement('canvas');
                crop_canvas.width = width;
                crop_canvas.height = height;
                  
                crop_canvas.getContext('2d').drawImage(image_target, left, top, width, height, 0, 0, width, height);
                var canvas_img=crop_canvas.toDataURL();
            $.ajax({
                type:"POST",
                url:"/shop/add_cm",
                data:{
                  image: canvas_img,
                  title: title
                },
                success:function(data){
                    location.reload();
                }
            });
        });
        
        /* crop = function(e){
          //Find the part of the image that is inside the crop box
          
        } */
      
        init();
    };

    /* cm_upload */
    $('#imagetemp').change(function(){
        readimage(this);
    })
    function readimage(file){
        if(file.files && file.files[0]){
            var reader = new FileReader();
            reader.onload=function(e){
                $('#image_preview').attr('src',e.target.result);
                resizeableImage($('#image_preview'));
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
        var stock_height = document.querySelector('.stock').clientHeight;
        var stock_width = document.querySelector('.stock').clientWidth;
        e.preventDefault();
        showLoader(stock_height-50,stock_width);
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
                    a.parents().parents().children('#price').html(data.price+' ¥');
                }
                if(data.total){
                    a.parents().parents().children('#total').html(data.total+' ¥');
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
        showLoader(stock_height-50,stock_width);
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
                    a.parents().parents().children('#price').html(data.price+'¥');
                }
                if(data.total){
                    a.parents().parents().children('#total').html(data.total+'¥');
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
        $.ajax(url,{
            success:function(){
                a.parents('tr').fadeOut();
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
/* cm upload */
/* $(function(){
    var modal = document.getElementById("cm_upload_modif");
    $(document).on('click','#cm_modif',function(){
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
    $('#imagetemp_modif').change(function(){
        readimage(this);
    })
    function readimage(file){
        if(file.files && file.files[0]){
            var reader = new FileReader();
            reader.onload=function(e){
                $('#image_preview_modif').attr('src',e.target.result);
            }
            reader.readAsDataURL(file.files[0]);
        }
    }
}); */
/* /cm upload */
/* navbar */
$(function(){
    var nav = document.querySelector('.nav');
    if(nav==null){
        return ;
    }
    var nav_height = nav.clientHeight;
    if($(window).scrollTop()>80){
        $('.navbar').css({"top":"0"});
    }else if($(window).scrollTop()<nav_height){
        var distance = nav_height-$(window).scrollTop();
        $('.navbar').css({"top":distance+"px"});
    }
    $(window).scroll(function(){
        if($(window).scrollTop()>80){
            $('.navbar').css({"top":"0"});
        }else if($(window).scrollTop()<80){
            var distance = nav_height-$(window).scrollTop();
            $('.navbar').css({"top":distance+"px"});
        }
    });
});
/* /navbar */

/* loader */
function showLoader(height,width){
    const loader = $('.loader');
    loader.css({"width":width});
    loader.css({"height":height});
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
function showLoadershop(loader,height,width){
    loader.css({"width":width});
    loader.css({"height":height});
    loader.addClass('loading');
    if(loader == null){ return }
    loader[0].style.display='block';
    loader.attr('aria-hidden','false');
}(jQuery)
function hideLoadershop(loader){
    loader.remove('loading');
    if(loader == null){ return }
    loader.attr('aria-hidden','true');
    loader[0].style.display='none';
}(jQuery)
/* /loader */
$(function(){
    $(document).on('click','.close-error',function(e){
        var cart_error = $('.cart-error');
        cart_error[0].style.display='none';
    });
});
$(function(){
    $(document).on('click','#del_adm',function(e){
        e.preventDefault();
        var del_adm = $('#del_adm');
        var cart_error = $('.cart-error');
        var url = del_adm.attr('href');
        $('.error-message').html('<p>この管理者を本当に削除しますか？</p><button><a class="delete_adm cart_delete" href="'+url+'">削除</a></button>');
        cart_error[0].style.display='block';
    });
});
/* add_article_cart */
$(function(){
    $(document).on('click','#add',function(e){
        var products_height = document.querySelector('.content').clientHeight;
        var products_width = document.querySelector('.content').clientWidth;
        e.preventDefault();
        showLoader(products_height,products_width);
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
$(function(){
    $(document).on('click','#add-shop',function(e){
        var products_height = document.querySelector('.shop_products').clientHeight;
        var products_width = document.querySelector('.shop_products').clientWidth;
        e.preventDefault();
        var loader = $('.loader-shop');
        showLoadershop(loader,products_height,products_width);
        var url=$(this).attr('href');
        $.ajax({
            url:url,
            type:'POST',
            dataType:'json',
            success:function(data){
                if(data.success){
                    val1 = $(data.success);
                    $('#in-cart-shop').html(val1);
                    val1.hide().fadeIn();
                }
                if(data.error){
                    error = $(data.error);
                    $('#error-cart').html(error);
                    var cart_error = $('.cart-error');
                    cart_error[0].style.display='block';
                }
                hideLoadershop(loader);
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
    $('#commercial').change(function(){
        readimage(this);
    });
    function readimage(file){
        if(file.files && file.files[0]){
            var reader = new FileReader();
            reader.onload=function(e){
                $('#cm_image').attr('src',e.target.result);
            }
            reader.readAsDataURL(file.files[0]);
        }
    }
});
$(function(){
    var count = 1;
    $(document).on('click','#product-next',function(event){
            count++;
        event.preventDefault();
        showLoader();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            method:"POST",
            data:{count:count},
            dataType:"json",
            success:function(data){
                if(data.success){
                    $('.products').html(data.success);
                    hideLoader();
                }
                if(data.error){
                    $('#err').html(data.error);
                    hideLoader();
                }
            }
        });
    });
    $(document).on('click','#product-prev',function(event){
            count--;
        event.preventDefault();
        showLoader();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            method:"POST",
            data:{count:count},
            dataType:"json",
            success:function(data){
                if(data.success){
                    $('.products').html(data.success);
                    hideLoader();
                }
            }
        });
    });
});
$(function(){
    var count = 1;
    $(document).on('click','#product-s-next',function(event){
            count++;
        event.preventDefault();
        showLoader();
        url = $(this).attr('href');
        $.ajax({
            url:url,
            method:"POST",
            data:{count:count},
            dataType:"json",
            success:function(data){
                if(data.success){
                    $('.products').html(data.success);
                    hideLoader();
                }
                if(data.error){
                    $('#err').html(data.error);
                }
            }
        });
    });
    $(document).on('click','#product-s-prev',function(event){
            count--;
        event.preventDefault();
        showLoader();
        url = $(this).attr('href');
        $.ajax({
            url:url,
            method:"POST",
            data:{count:count},
            dataType:"json",
            success:function(data){
                if(data.success){
                    $('.products').html(data.success);
                    hideLoader();
                }
            }
        });
    });
});
$(function() {
    var pause = false;
    $('#next').click(function(){
        pause = true;
        $('.slide-content > .slide-img:first')
        .fadeOut(1000)
        .next()
        .fadeIn(1000)
        .end()
        .appendTo('.slide-content');
    });
    $('#prev').click(function(){
        pause = true;
        $('.slide-content > .slide-img:last')
        .fadeIn(1000)
        .prependTo('.slide-content')
        .next()
        .fadeOut(1000)
        .end();
    });
    setInterval(function(){
        if(pause === false){
            $('.slide-content > .slide-img:first')
            .fadeOut(1000)
            .next()
            .fadeIn(1000)
            .end()
            .appendTo('.slide-content');
        };
    },5000);
});
/* cm upload */

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
$(function(){
    const price = document.getElementById("art_price");
    if(price == null){ return }
    price.addEventListener('input',function(){
        var numval = price.value.replace(/\D/g,"");
        string_numval = numval.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        price.value = string_numval;
        new_price = price.value.replace(/\D/g,"");
        
    })
});
function addbreak(){
    price = document.getElementById("art_price");
    desc = document.getElementById("desc");
    text = document.getElementById("description").value;
    new_price = price.value.replace(",","");
    text = text.replace(/   /g,"[sp][sp]");
    text = text.replace(/\n/g,"[nl]");
    document.getElementById("art_price").value = new_price;
    desc.value = text;
}
function breack(){
    text = document.getElementById("description").value;
    desc = document.getElementById("desc");
    text = text.replace(/   /g,"[sp][sp]");
    text = text.replace(/\n/g,"[nl]");
    desc.value = text;
}
function show_name(){
    var div = document.getElementById("change_name");
    if(div.style.display === "block"){
        div.style.display="none";
    }else{
        div.style.display="block";
    }
}
function show_email(){
    var div = document.getElementById("change_email");
    if(div.style.display === "block"){
        div.style.display="none";
    }else{
        div.style.display="block";
    }
}
function show_phone(){
    var div = document.getElementById("change_phone");
    if(div.style.display === "block"){
        div.style.display="none";
    }else{
        div.style.display="block";
    }
}
function show_description(){
    var div = document.getElementById("change_description");
    if(div.style.display === "block"){
        div.style.display="none";
    }else{
        div.style.display="block";
    }
}
function show_web(){
    var div = document.getElementById("change_web");
    if(div.style.display === "block"){
        div.style.display="none";
    }else{
        div.style.display="block";
    }
}
function show_admins(){
    var div = document.getElementById("change_admins");
    if(div.style.display === "block"){
        div.style.display="none";
    }else{
        div.style.display="block";
    }
}
/* $(function($){
    $(document).on('keyup','#input-add',function(e){
        var input = $(this)
        var typed_name = input.val();
        $.ajax({
            url:"/shop/users_admin",
            method:"POST",
            data:{"name":typed_name},
            dataType:"json",
            success:function(data){
                if(data.val){
                    autocomplete(input,data.val);
                }
            }
        });
    });

}); */
$(function($){
    var input = document.getElementById("input-add");
    if(!input) return false;
    input.addEventListener("input", function(e){
        var inp = this;
        var typed_name = input.value;
        var currentFocus;
        currentFocus=-1;
        $.ajax({
            url:"/shop/users_admin",
            method:"POST",
            data:{"name":typed_name},
            dataType:"json",
            success:function(data){
                if(data.val){
                    autocomplete(inp, data.val,currentFocus);
                }
            }
        });
    });
    function autocomplete(inp, result,currentFocus){
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var a, b, i, val = inp.value;
        /*execute a function when someone writes in the text field:*/
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByClassName("autocomplete-items-class");
        addActive(x);
        if (!val) { return false;}
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", inp.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        inp.parentNode.appendChild(a);
        /*for each item in the array...*/
            /*for each item in the array...*/
            for (i = 0; i < result.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                if (result[i]['email'].substr(0, val.length).toUpperCase() == val.toUpperCase()) {

                    /*create a DIV element for each matching element:*/
                    /*make the matching letters bold:*/
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    b.setAttribute("class","autocomplete-items-class")
                    b.innerHTML+="<div class='admins'><div class='admin-image-search'><img src='/"+result[i]['logo_type']+"' /></div>";
                    b.innerHTML+="<div class='admin-name-search'><span><strong>" + result[i]['email'].substr(0, val.length) +"</strong>"+result[i]['email'].substr(val.length)+"</span><br/><span>"+ result[i]['firstname']+" "+result[i]['lastname']+"</span></div>";
                    b.innerHTML+="</div>";
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input class ='inph' type='hidden' value='" + result[i]['email'] + "'>";
                     /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByClassName("inph")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if(!x) return false;
            if (x) x = x.getElementsByClassName("autocomplete-items-class");
            /* console.log(currentFocus); */
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                var y = document.getElementsByClassName("autocomplete-active")[0];
                if(!y) return false;
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (y) y.click();
                }
            }
        });
        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                } 
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }
});

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


