var $ = jQuery.noConflict();
function ajaxLoaderStart()
{
    if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
        jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="' + tempurl + '/images/ajax-loader.gif"><div></div></div><div class="bg"></div></div>');
    }

    jQuery('#resultLoading').css({
        'width':'100%',
        'height':'100%',
        'position':'fixed',
        'z-index':'10000000',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto'
    });

    jQuery('#resultLoading .bg').css({
        'background':'#000000',
        'opacity':'0.7',
        'width':'100%',
        'height':'100%',
        'position':'absolute',
        'top':'0'
    });

    jQuery('#resultLoading>div:first').css({
        'width': '250px',
        'height':'75px',
        'text-align': 'center',
        'position': 'fixed',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto',
        'font-size':'16px',
        'z-index':'10',
        'color':'#ffffff'

    });

    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeIn(300);
    jQuery('body').css('cursor', 'wait');
}

function ajaxLoaderStop()
{
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeOut(300);
    jQuery('body').css('cursor', 'default');
}

jQuery(document).ready(function($){
    var wk_id = '';
    wk_id = $('#workout_cat option:selected').val();

    if(typeof wk_id !== "undefined"){
        if(wk_id!=''){
            get_workout_lists(wk_id,'workout_name');
        }
    }

    if( jQuery('#completed_date').length > 0)
    {
        jQuery('#completed_date').datepicker({
            dateFormat : 'yy-mm-dd',
            maxDate: "D",
            onSelect:function(date){
                $('#addWorkoutPB').formValidation('revalidateField', 'completed_date');
            }
        });

    }










    $('#lostpassword').formValidation({
        framework: 'bootstrap',
        fields: {
            user_login: {
                validators: {
                    notEmpty: {
                        message: 'Email address is required and cannot be empty'
                    },
                    emailAddress: {
                        message: 'Email address is not a valid.'
                    },
                }
            }
        }
    }) .on('success.form.fv', function(e) {
        // Prevent form submission
        var set_flag = true;
        e.preventDefault();
        // Get the form instance
        var $form = jQuery(e.target);

        // Get the FormValidation instance
        var bv = $form.data('formValidation');
        // Use Ajax to submit form data
        var frmdata = jQuery("#lostpassword").serializeArray();
        frmdata.push({name:'action',value:'lostpassword'});
        ajaxLoaderStart();
        if(set_flag){
            jQuery.post(ajaxurl, frmdata, function(result) {
                response = result;
                if(response.success && response.class == 'alert-success'){
                    $("#lgnMessage").removeClass("alert-danger alert-success").addClass(response.class);
                    $('#lgnMessage').html(response.message).show();
                    $('#lgnMessage').fadeOut(15000).delay(20000);
                    ajaxLoaderStop();
                }
                else{
                    $("#lgnMessage").removeClass("alert-danger alert-success").addClass(response.class);
                    $('#lgnMessage').html(response.message).show();
                    ajaxLoaderStop();
                }
                $form
                    .formValidation('disableSubmitButtons', false)
                    .formValidation('resetForm', true);
            }, 'json');
        }

    });

    $('#resetpass').formValidation({
        framework: 'bootstrap',
        fields: {
            pass1: {
                validators: {
                    notEmpty: {
                        message: 'Password is required and cannot be empty'
                    },
                    identical: {
                        field: 'pass2',
                        message: 'The Password and its confirm are not the same'
                    }
                }
            },
            pass2: {
                validators: {
                    notEmpty: {
                        message: 'Confirm password is required and cannot be empty'
                    },
                    identical: {
                        field: 'pass1',
                        message: 'The password and its confirm are not the same'
                    }
                }
            },
        }
    }) .on('success.form.fv', function(e) {
        // Prevent form submission
        var set_flag = true;
        e.preventDefault();
        // Get the form instance
        var $form = jQuery(e.target);

        // Get the FormValidation instance
        var bv = $form.data('formValidation');
        // Use Ajax to submit form data
        var frmdata = jQuery("#resetpass").serializeArray();
        frmdata.push({name:'action',value:'resetpass'});
        ajaxLoaderStart();
        if(set_flag){
            jQuery.post(ajaxurl, frmdata, function(result) {
                response = result;
                if(response.success && response.class == 'alert-success'){
                    $("#lgnMessage").removeClass("alert-danger alert-success").addClass(response.class);
                    $('#lgnMessage').html(response.message).show();
                    $('#lgnMessage').fadeOut(15000).delay(20000);
                    ajaxLoaderStop();
                    if(response.redirect != ''){
                        window.location.href=response.redirect;
                    }else{
                        window.location.href=siteurl;
                    }
                }
                else{
                    $("#lgnMessage").removeClass("alert-danger alert-success").addClass(response.class);
                    $('#lgnMessage').html(response.message).show();
                    ajaxLoaderStop();
                }
                $form
                    .formValidation('disableSubmitButtons', false)
                    .formValidation('resetForm', true);
            }, 'json');
        }
    });




    //add add_more functionality
    jQuery(document).ready(function(){

        jQuery('.add_more').click(function(){
            var copy_html = jQuery('.clone_data').html();
            var num_row = jQuery('#append_data .row_data').length;
            var index_val = parseInt(parseInt(num_row)+ 2);
            jQuery('#append_data').append(copy_html);

            //console.log(copy_html);
            jQuery("#append_data .row_data:last-child").find("input,select").each(function(){
                var field_name = jQuery(this).attr('name');
                field_name = field_name.replace(/\d/,index_val);
                jQuery(this).attr('name',field_name);
            });
            jQuery("#append_data .row_data:last-child").find(".remove").removeClass('hide');
            jQuery('#append_data .row_data:last-child').find("input[type='text'],select").val('');
            jQuery('#append_data .row_data:last-child').find('.tr_reps, .tr_weight, .tr_times, .tr_box_jump, .tr_distance').addClass('hide');


        });
        jQuery(document).on('click','.remove', function(){
            jQuery(this).parents('.row_data').remove();
            var r=1;
            jQuery('#append_data .row_data').each(function(){

                jQuery(this).find('input,select').each(function(){
                    var field_name = jQuery(this).attr('name');
                    field_name = field_name.replace(/\d/,r);
                    jQuery(this).attr('name',field_name);
                });

                r++;
            });

        });

        //add validation
        jQuery("body").delegate('.field_required', 'keyup , change', function($){
            var isval =jQuery(this).val().trim();
           // alert(isval);
            if(!isval)
            {
               jQuery(this).addClass('has_error');
            }
            else
            {

                jQuery(this).removeClass('has_error');
            }
        });

        jQuery('.submit_PB').click(function(e){
            e.preventDefault();
            var has_error = false;
            var index = 0;
            jQuery('.field_required').each(function(){

                if(jQuery(this).is(':visible'))
                {

                    var isval =jQuery(this).val().trim();

                    if(!isval)
                    {
                        has_error = true;
                        jQuery(this).addClass('has_error');
                        if(index == 0)
                        {
                            jQuery(this).focus();
                            index++;
                        }


                    }
                    else
                    {
                        jQuery(this).removeClass('has_error');


                        if(jQuery(this).attr('type') == 'number')
                        {
                            var number_field = jQuery(this).val();
                            if(!jQuery.isNumeric(number_field))
                            {
                                jQuery(this).addClass('has_error');

                            }

                        }

                    }
                }
            });

            if(has_error)
            {
                return false;
            }
            else
            {
                //Add a code to post data

                var frmdata = jQuery("#addWorkoutPB").serializeArray();
                frmdata.push({name:'action',value:'addWorkoutPB'});
                ajaxLoaderStart();

                jQuery.post(ajaxurl, frmdata, function(result) {
                    response = result;
                    if(response.success && response.class == 'alert-success'){
                        $("#addWkMessage").removeClass("alert-danger alert-success").addClass(response.class);
                        $('#addWkMessage').html(response.message).show();
                        $('#addWkMessage').delay(5000).fadeOut(5000);
                        $('#addWorkoutPB')[0].reset();
                        //jQuery("#addWorkoutPB input:text,#addWorkoutPB textarea,#addWorkoutPB select").val('');
                        jQuery(".tr_reps ,.tr_weight ,.tr_times ,.tr_box_jump ,.tr_distance").addClass('hide');

                        jQuery('#append_data').html('');
                        ajaxLoaderStop();
                    }
                    else{
                        $("#addWkMessage").removeClass("alert-danger alert-success").addClass(response.class);
                        $('#addWkMessage').html(response.message).show();
                        ajaxLoaderStop();
                    }

                }, 'json');



            }

        });



        //Add functionality for gender show hide


        jQuery("body").delegate('#user_type', 'change', function($){
            var val =jQuery(this).val().trim();

            if(val=='normal_user')
            {
                jQuery('.user_gender_for_normal_user').slideDown('slow');
                jQuery('.user_gender_for_normal_user').find('#gender').attr('required', "required");
            }
            else
            {
                jQuery('.user_gender_for_normal_user').slideUp('slow');
                jQuery('.user_gender_for_normal_user').find('#gender').removeAttr('required');

            }
        });

    });



    var	ReviewImgTemp = new Array();
    var options = {
        beforeSend: function()
        {
            ajaxLoaderStart();
        },
        uploadProgress: function(event, position, total, percentComplete)
        {
        },
        url: ajaxurl,
        type: 'post',
        dataType:  'json',
        success: function(data)
        {
            $('#userPic').val('');
            ajaxLoaderStop();
            if(data)
            {
                var response = data;
                if(response.error)
                {
                    $("#userprofilePic").html(response.error);
                }
                if(response.success)
                {
                    if(response.html != ''){
                        $('#userprofilePic').removeClass('has_error');
                        $('#userprofilePic').html(response.html).show();
                        $('#profilePicUpload , #editProfilePic').hide();
                    }
                    // deleteUserPic();
                }
            }
        },
        complete: function(data)
        {
            //When user pic Change then update header image.
            $('#updateUserPicHeader').attr('src', $('#userprofilePic').find('img').attr('src')) ;
        },
        error: function()
        {
            $("#UploadResponseError").html("ERROR: unable to upload files");
        }
    };
    $("#profilePicUpload").ajaxForm(options);

    $( "body" ).delegate( "#upload,#editProfilePic", "click", function() {
        $('#userPic').trigger('click');
    });
    $( "body" ).delegate( "#userPic", "change", function() {
        $('#updateUserPic').trigger('click');
    });





    $('#trainer_form').formValidation({
        fields: {
            gym_name: {
                validators: {
                    notEmpty: {
                        message: 'Gym name is required and cannot be empty'
                    }
                }
            }
        }
    }).on('success.form.fv', function(e) {
        // Prevent form submission
        var set_flag = true;
        e.preventDefault();
        // Get the form instance
        var $form = jQuery(e.target);

        // Get the FormValidation instance
        var bv = $form.data('formValidation');
        // Use Ajax to submit form data
        var frmdata = jQuery("#trainer_form").serializeArray();
        frmdata.push({name:'action',value:'trainerform'});

        if(set_flag){
            jQuery.post(ajaxurl, frmdata, function(result) {
                response = result;
                //alert(response.message);
                //SiteUrl = SiteUrl.replace(/\/$/, '');
                if(response.success && response.class == 'alert-success'){
                    $('.reg_success').html(response.message);
                    $("#gym_name").selectmenu("refresh");
                }
                if(response.success && response.class == 'alert-danger'){
                    $('.reg_error').html(response.message);
                }
                if(response.success && response.class == 'error'){
                    $('.reg_error').html(response.message);
                }
                $form
                    .formValidation('disableSubmitButtons', false) // Enable the submit buttons
                    .formValidation('resetForm', true);
            }, 'json');
        }

    });

    $('#useroption_form').formValidation({
        fields: {
            trainer_id: {
                validators: {
                    notEmpty: {
                        message: 'Personal Trainer is required and cannot be empty'
                    }
                }
            },
            /*gender: {
                validators: {
                    notEmpty: {
                        message: 'Sex Field is required and cannot be empty'
                    }
                }
            }*/
        }
    }).on('success.form.fv', function(e) {
        // Prevent form submission
        var set_flag = true;
        e.preventDefault();
        // Get the form instance
        var $form = jQuery(e.target);

        // Get the FormValidation instance
        var bv = $form.data('formValidation');
        // Use Ajax to submit form data
        var frmdata = jQuery("#useroption_form").serializeArray();
        frmdata.push({name:'action',value:'useroptionform'});

        if(set_flag){
            jQuery.post(ajaxurl, frmdata, function(result) {
                response = result;
                //alert(response.message);
                //SiteUrl = SiteUrl.replace(/\/$/, '');
                if(response.success && response.class == 'alert-success'){
                    $('.reg_success').html(response.message);
                    $("#trainer_id").selectmenu("refresh");
                    $("input[type='radio']").attr("checked", true).checkboxradio("refresh");
                }
                if(response.success && response.class  == 'alert-danger'){
                    $('.reg_error').html(response.message);
                }
                if(response.success && response.class == 'error'){
                    $('.reg_error').html(response.message);
                }
                $form
                    .formValidation('disableSubmitButtons', false) // Enable the submit buttons
                    .formValidation('resetForm', true);
            }, 'json');
        }

    });



    $('#useroption_form').formValidation({
        fields: {
            trainer_id: {
                validators: {
                    notEmpty: {
                        message: 'Personal Trainer is required and cannot be empty'
                    }
                }
            },
            /*gender: {
             validators: {
             notEmpty: {
             message: 'Sex Field is required and cannot be empty'
             }
             }
             }*/
        }
    }).on('success.form.fv', function(e) {
        // Prevent form submission
        var set_flag = true;
        e.preventDefault();
        // Get the form instance
        var $form = jQuery(e.target);

        // Get the FormValidation instance
        var bv = $form.data('formValidation');
        // Use Ajax to submit form data
        var frmdata = jQuery("#useroption_form").serializeArray();
        frmdata.push({name:'action',value:'useroptionform'});

        if(set_flag){
            jQuery.post(ajaxurl, frmdata, function(result) {
                response = result;
                //alert(response.message);
                //SiteUrl = SiteUrl.replace(/\/$/, '');
                if(response.success && response.class == 'alert-success'){
                    $('.reg_success').html(response.message);
                    $("#trainer_id").selectmenu("refresh");
                    $("input[type='radio']").attr("checked", true).checkboxradio("refresh");
                }
                if(response.success && response.class  == 'alert-danger'){
                    $('.reg_error').html(response.message);
                }
                if(response.success && response.class == 'error'){
                    $('.reg_error').html(response.message);
                }
                $form
                    .formValidation('disableSubmitButtons', false) // Enable the submit buttons
                    .formValidation('resetForm', true);
            }, 'json');
        }

    });



    jQuery(".social-box a").on('click',function(){
        window.open( jQuery(this).attr('href'),  '_blank' );
        return true;
    });

});

function get_workout_lists(id,sid,obj){
    ajaxLoaderStart();
    jQuery.ajax({
        type	: "POST",
        cache	: false,
        url     : ajaxurl,
        dataType : 'json',
        data: {
            'action' : 'get_workout_lists',
            'cid':id,
            'sid':sid
        },
        success: function(data) {
            ajaxLoaderStop();
            obj.parents('.row_data').find('.tr_reps, .tr_weight, .tr_times, .tr_box_jump, .tr_distance').find('input[type="text"] , select , input[type="number"]').val('');

            obj.parents('.row_data').find('.tr_reps, .tr_weight, .tr_times, .tr_box_jump, .tr_distance').addClass('hide');
            if(data.states!=''){
                if(data.set){
                    obj.parents('.row_data').find('.'+data.id).html(data.states);
                }
            }
            else{
                obj.parents('.row_data').find('.tr_reps,.tr_weight,.tr_times,.tr_box_jump,.tr_distance').find('input[type="text"] , select , input[type="number"]').val('');

                obj.parents('.row_data').find('.tr_reps,.tr_weight,.tr_times,.tr_box_jump,.tr_distance').addClass('hide');
            }
        }         
    });
}

function workout_fields(obj,new_obj){
    var id  = $(obj).val();

    ajaxLoaderStart();
    jQuery.ajax({
        type	: "POST",
        cache	: false,
        url     : ajaxurl,
        dataType : 'json',
        data: {
            'action' : 'enabled_disabled',
            'post_id':id,
        },
        success: function(data) {
            ajaxLoaderStop();
            var workout_cat =  new_obj.parents('.row_data').find('.workout_cat option:selected').val();
            if(data!=''){
  
                if(workout_cat == 'cardio'){
                    new_obj.parents('.row_data').find('.tr_box_jump').removeClass('hide');
                    new_obj.parents('.row_data').find('.addWorkoutPB').formValidation('revalidateField', new_obj.parents('.row_data').find('.tr_box_jump').find('input[type="text"]').attr('name'));
                }
                else if(workout_cat == 'running'){
                    new_obj.parents('.row_data').find('.tr_distance').removeClass('hide');
                    new_obj.parents('.row_data').find('.addWorkoutPB').formValidation('revalidateField', new_obj.parents('.row_data').find('.tr_distance').find('input[type="text"]').attr('name'));
                }
                else{
                    new_obj.parents('.row_data').find('.tr_weight').removeClass('hide');
                    new_obj.parents('.row_data').find('.addWorkoutPB').formValidation('revalidateField', new_obj.parents('.row_data').find('.tr_weight').find('input[type="text"]').attr('name'));
                }

                new_obj.parents('.row_data').find('.weight_metter').html('');
                if(data.reps == 'yes'){
                    new_obj.parents('.row_data').find('.tr_reps').removeClass('hide');
                }
                if(data.weight == 'yes'){
                    new_obj.parents('.row_data').find('.weight_metter').html(data.weight_unit);
                }
                if(data.times == 'yes'){
                    new_obj.parents('.row_data').find('.tr_times').removeClass('hide');
                }
            }
            else{

                new_obj.parents('.row_data').find('.tr_reps,.tr_weight,.tr_times,.tr_box_jump,.tr_distance').find('input[type="text"] , select , input[type="number"]').val('');


                new_obj.parents('.row_data').find('.tr_reps,.tr_weight,.tr_times,.tr_box_jump,.tr_distance').addClass('hide');
            }
        }

    });


}


function getSingleWorkoutDetail(workout_date){
    ajaxLoaderStart();
    jQuery.ajax({
        type	: "POST",
        cache	: false,
        url     : ajaxurl,
        dataType : 'json',
        data: {
            'action' : 'workoutDetail',
            'workout_date':workout_date,
        },
        success: function(data) {
            ajaxLoaderStop();
            $('#workoutDetailModal').html(data.workout_detail);
            $('#workoutDetailModal').modal('show');
        }
    });
}

function get_personal_best_for_graph(id){
    ajaxLoaderStart();
    jQuery.ajax({
        type	: "POST",
        cache	: false,
        url     : ajaxurl,
        dataType : 'json',
        data: {
            'action' : 'get_personal_best_for_graph',
            'wid':id
        },
        success: function(data) {
            ajaxLoaderStop();
            if(data.bests!=''){
                if(data.set){
                    console.log(data.bests);
                }
            }
        }
    });
}

function deleteUserPic(obj){
    var data_id = jQuery(obj).attr('data-upload_id');
    ajaxLoaderStart();
    jQuery.ajax({
        type:"POST",
        url:ajaxurl,
        dataType:'json',
        data:{'action':'deleteUserPic','attach_id':data_id },
        success:function(data){
            if(data.success){
                ajaxLoaderStop();
                jQuery('#userprofilePic').html(data.html)
            }
        }
    });
}
