baseUrl = 'http://127.0.0.1:8000/system';
function pageAlert($selector, $type, $message) {

    if ($type == 'hide') {
        $($selector).hide();
    } else if ($type == 'error') {
        $($selector).html("<div class=\"alert alert-danger fade show\" role=\"alert\">\n" +
            "                                <div class=\"alert-icon\"><i class=\"flaticon-warning\"></i></div>\n" +
            "                                <div class=\"alert-text\">" + $message + "</div>\n" +
            "                                <div class=\"alert-close\">\n" +
            "                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
            "                                        <span aria-hidden=\"true\"><i class=\"la la-close\"></i></span>\n" +
            "                                    </button>\n" +
            "                                </div>\n" +
            "                            </div>").show();
    } else if ($type == 'success') {
        $($selector).html("<div class=\"alert alert-success fade show\" role=\"alert\">\n" +
            "                                <div class=\"alert-icon\"><i class=\"flaticon-warning\"></i></div>\n" +
            "                                <div class=\"alert-text\">" + $message + "</div>\n" +
            "                                <div class=\"alert-close\">\n" +
            "                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
            "                                        <span aria-hidden=\"true\"><i class=\"la la-close\"></i></span>\n" +
            "                                    </button>\n" +
            "                                </div>\n" +
            "                            </div>").show();
    }

}
function formSubmit($url, $data, $success, $error) {
    pageAlert('#form-alert-message', 'hide');
    
    $("[id$='-form-input']").removeClass('is-invalid');
    $("[id$='-form-error']").html('');

    // if ($method == 'get') {
    //     alert(22);
    // } else {
       
    if (isObject($data)) {
     
        $options = {
            url: $url,
            type: 'POST',
            data: $data,
            processData: false,
            contentType: false,
            success: function($response) {
                 
                if ($success) {
                    $('#success-alert-modal').modal('show');
                    document.querySelector("#succ-btn").addEventListener('click', function(e) {
                    $success($response);
                });
                }
            },
            error: function($response) {
                 
                if ($error) {
                    $error($response);
                }

                pageAlert('#form-alert-message', 'error', 'Some fields are invalid please fix them');

                var errors = $.parseJSON($response.responseText);
                $.each(errors.errors, function(key, value) {
                    $('#' + key + '-form-input').addClass('is-invalid');
                    $('#' + key + '-form-error').html(value.join('<br />'));
                });

            }
        };
    } else {
          
        $options = {
            url: $url,
            type: 'POST',
            data: $data,
            success: function($response) {
                if ($success) {
                    $success($response);
                }
            },
            error: function($response) {
            
                if ($error) {
                    $error($response);
                }

                pageAlert('#form-alert-message', 'error', 'Some fields are invalid please fix them');

                var errors = $.parseJSON($response.responseText);
                $.each(errors.errors, function(key, value) {
                    $('#' + key + '-form-input').addClass('is-invalid');
                    $('#' + key + '-form-error').html(value.join('<br />'));
                });

            }
        }
    }

    // }
    // addLoading();
    
    $.ajax($options).fail(function($response) {
        //  
        if ($error) {
            
            $error($response);
        }

        pageAlert('#form-alert-message', 'error', 'Some fields are invalid please fix them');

        var errors = $.parseJSON($response.responseText);
        $.each(errors.errors, function(key, value) {
            $('#' + key + '-form-input').addClass('is-invalid');
            $('#' + key + '-form-error').html(value.join('<br />'));
        });

    });

    /* $.post($url,$data,function($response){
          
         if($success){
             $success($response);
         }
     }).fail(function($response){
          
         if($error){
             $error($response);
         }

         pageAlert('#form-alert-message','error','Some fields are invalid please fix them');

         var errors = $.parseJSON($response.responseText);
         $.each(errors.errors, function (key, value) {
             $('#'+key+'-form-input').addClass('is-invalid');
             $('#' + key + '-form-error').html(value.join('<br />'));
         });

     });*/
}
function formSubmitCar($url, $data, $success, $error) {
    
    pageAlert('#form-alert-message', 'hide');
    
    $("[id$='-form-input']").removeClass('is-invalid');
    $("[id$='-form-error']").html('');

    // if ($method == 'get') {
    //     alert(22);
    // } else {
       
    if (isObject($data)) {
     
        $options = {
            url: $url,
            type: 'POST',
            data: $data,
            processData: false,
            contentType: false,
            success: function($response) {
                 
                if ($success) {
                    $('#success-alert-modal').modal('show');
                    document.querySelector("#succ-btn").addEventListener('click', function(e) {
                    $success($response);
                });
                }
            },
            error: function($response) {
                 
                if ($error) {
                    $error($response);
                }

                pageAlert('#form-alert-message', 'error', 'Some fields are invalid please fix them');

                var errors = $.parseJSON($response.responseText);
                $.each(errors.errors, function(key, value) {
                    $('#' + key + '-form-input').addClass('is-invalid');
                    $('#' + key + '-form-error').html(value.join('<br />'));
                });

            }
        };
    } else {
          
        $options = {
            url: $url,
            type: 'POST',
            data: $data,
            success: function($response) {
                if ($success) {
                    $success($response);
                }
            },
            error: function($response) {
            
                if ($error) {
                    $error($response);
                }

                pageAlert('#form-alert-message', 'error', 'Some fields are invalid please fix them');

                var errors = $.parseJSON($response.responseText);
                $.each(errors.errors, function(key, value) {
                    $('#' + key + '-form-input').addClass('is-invalid');
                    $('#' + key + '-form-error').html(value.join('<br />'));
                });

            }
        }
    }

    // }
    // addLoading();
    
    $.ajax($options).fail(function($response) {
        //  
        if ($error) {
            
            $error($response);
        }

        pageAlert('#form-alert-message', 'error', 'Some fields are invalid please fix them');

        var errors = $.parseJSON($response.responseText);
        $.each(errors.errors, function(key, value) {
            $('#' + key + '-form-input').addClass('is-invalid');
            $('#' + key + '-form-error').html(value.join('<br />'));
        });

    });

    /* $.post($url,$data,function($response){
          
         if($success){
             $success($response);
         }
     }).fail(function($response){
          
         if($error){
             $error($response);
         }

         pageAlert('#form-alert-message','error','Some fields are invalid please fix them');

         var errors = $.parseJSON($response.responseText);
         $.each(errors.errors, function (key, value) {
             $('#'+key+'-form-input').addClass('is-invalid');
             $('#' + key + '-form-error').html(value.join('<br />'));
         });

     });*/
}
function isObject(A) {
    if ((typeof A === "object" || typeof A === 'function') && (A !== null)) {
        return true;
    }

    return false;
}

// function addLoading() {
//     $.blockUI({
//         css: {
//             border: 'none',
//             padding: '15px',
//             backgroundColor: '#000',
//             '-webkit-border-radius': '10px',
//             '-moz-border-radius': '10px',
//             opacity: .5,
//             color: '#fff'
//         }
//     });
// }

// function removeLoading() {
//     $.unblockUI();
// }

function tablePagination($url,$onDone){
    $.get($url,{'isTablePagination':true},function($html){
        $('#table-pagination-div').html($html);
        if($onDone !== undefined){
            $onDone();
        }
        feather.replace();
        // removeLoading();
    });
}


function tablePaginationFilter($form) {
    $url = window.location.href;
    // $('#filter-collapse').collapse('hide');
    if ($url.indexOf('?') != -1) {
        tablePagination($url + '&' + $form.serialize());
    } else {
        tablePagination($url + '?' + $form.serialize());
    }
}
function isJSON(m) {
    if (typeof m == 'object') {
        try { m = JSON.stringify(m); } catch (err) { return false; }
    }

    if (typeof m == 'string') {
        try { m = JSON.parse(m); } catch (err) { return false; }
    }

    if (typeof m != 'object') { return false; }
    return true;

};

