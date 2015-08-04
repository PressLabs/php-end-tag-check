var $ = jQuery.noConflict();

$( document ).ready( function()
        {
            $('#dismiss-btn').click( function (){
                    $.ajax({

                            url: php_cleaner_ajax.ajaxurl,
                            type: "POST",
                            data:{
                                action: "dismiss_php_cleaner",
                                nonce: php_cleaner_ajax.ajax_nonce
                            },
                            success: function(){
                                $('#notif22').remove();
                                if(window.location.pathname == '/wp-admin/plugins.php')
                                {
                                    location.reload();
                                }
                            }
                        }
                        )
            })
        }
        );

