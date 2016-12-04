<?php
/*
 *  Template Name:Add PB
 */
if (!is_user_logged_in()) {
    wp_redirect(site_url());
    exit();
}
get_header();

?>
    <div class="clear"></div>

    <div class="bridcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url() ?>/mydashboard/">Home</a></li>
                <li class="active"><a><?php the_title(); ?></a></li>
            </ol>
        </div>
    </div>

    <div class="container">
        <div class="inner-content">
            <h2><?php if (get_post_meta(get_the_ID(), 'Title', true)) {
    echo get_post_meta(get_the_ID(), 'Title', true);
} else {
    echo get_the_title();
} ?></h2>
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <?php get_sidebar(); ?>
                </div>

                <div class="col-md-8 col-sm-8">
                      <div class="personal-best add-Workout-PB">
                       <div id="addWkMessage" class="alert" style="display: none;"></div>
                        <form action="" method="POST" id="addWorkoutPB" name="addWorkoutPB">
                            <div class="best1 workPB-box" width="100%" border="0" cellspacing="0" cellpadding="0">

                                <div class="labelBox">
                                    <label>Select Date *</label>
                                    <div><div class="form-group"><input name="completed_date" id="completed_date"
                                                                        value="" class="form-control field_required" type="text" ></div></div>
                                </div>
                                                          
    <div  class="clone_data">
      
                                    <div class="row_data"> 
                                        <div class="labelBox">
                                            <label>Select Workout Category *</label>
                                            <div>
                                                <div class="form-group">
                                                    <select class="form-control workout_cat field_required" placeholder=""  name="PERSONALBEST[1][workout_cat]" onchange="get_workout_lists_pb_specific(this.value,'workout_name',jQuery(this));">
                                                        <option selected value="">-- Select Type --</option>

    <option value="strength">Strength</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="labelBox">
                                            <label>Select Workout Type *</label>
                                            <div>
                                                <div class="form-group">
                                                    <select class="form-control workout_name field_required" placeholder=""
                                                            name="PERSONALBEST[1][workout_name]"
                                                            onchange="workout_fields(this,jQuery(this));">
                                                        <option value="" selected>-- Select Type --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tr_weight hide">
                                            <label>Weight *</label>
                                            <div>
                                                <div class="form-group">
                                                    <input type="number" min="0" step="any" class="form-control  weight field_required" name="PERSONALBEST[1][weight][text]"  style="float: left; margin-right: 10px; width:29%">
                                                    <select style="width: 20%;" class="form-control weight_metter"
                                                            placeholder="" name="PERSONALBEST[1][weight][unit]"></select>
                                                </div>
                                            </div>
                                        </div>
                                       <!-- <div class="tr_box_jump hide">
                                            <label>Box Jump</label>
                                            <div>
                                                <div class="form-group">
                                                    <input type="number" min="0"  class="form-control weight box_jump field_required" name="PERSONALBEST[1][box_jump][text]"  style="float: left; margin-right: 10px; width:29%">
                                                    <select style="width: 20%;" class="form-control weight_metter field_required"
                                                            placeholder=""  name="PERSONALBEST[1][box_jump][unit]"></select>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="tr_distance hide">
                                            <label>Distance *</label>
                                            <div>
                                                <div class="form-group">
                                                    <input type="number" step="any" min="0"  class="form-control distance field_required" name="PERSONALBEST[1][distance][text]"  style="float: left; margin-right: 10px; width:29%">
                                                    <select style="width: 20%;" class="form-control weight_metter field_required"
                                                            placeholder="" name="PERSONALBEST[1][distance][unit]"></select>
                                                </div>
                                            </div>
                                        </div>

                                      <!--  <div class="tr_reps hide">
                                            <label>Reps</label>
                                            <div><div class="form-group"> <input name="PERSONALBEST[1][reps]" min="0"
                                                                                 class="form-control reps field_required"
                                                                                 type="number" ></div></div>  
                                            
                                        </div>  -->
                                        <div class="tr_times hide">
                                        <label>Time Hours/Mins/Secs</label>
                                            <div class="form-group">
                                                <div  class="input-append time ">
                                                    <div class="hours">
                                                        <select name="PERSONALBEST[1][times][text][hours]" class="field_required form-control">
                                                            <option value="">Hours</option>
                                                            <?php
for ($i = 0; $i < 24; $i++) {
    $hour = ($i <= 9) ? '0' . $i : $i;
    ?>
    <option value="<?php echo $i ?>"><?php echo
        $hour;
        ?></option>
<?php
}
?>

                                                        </select>
                                                    </div>
                                                    <div class="mins">
                                                        <select name="PERSONALBEST[1][times][text][mins]" class="field_required form-control">
                                                            <option value="">Mins</option>
                                                            <?php
for ($i = 0; $i < 60; $i++) {
    $mins = ($i <= 9) ? '0' . $i : $i;
    ?>
    <option value="<?php echo $i ?>"><?php echo $mins;
        ?></option>
<?php
}
?>

                                                        </select>
                                                    </div>

                                                    <div class="secs">
                                                        <select name="PERSONALBEST[1][times][text][secs]" class="field_required form-control">
                                                            <option value="">Secs</option>
                                                            <?php
for ($i = 0; $i < 60; $i++) {
    $secs = ($i <= 9) ? '0' . $i : $i;
    ?>
    <option value="<?php echo $i ?>"><?php echo
        $secs;
        ?></option>
<?php
}
?>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                                <div class="form-group hidden">
                                                    <input name="PERSONALBEST[1][times][unit]"  class="form-control
                                                    field_required times" value="hours/mins/secs"  type="hidden" />
                                                </div>

                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <div class="publishPB"><label> <input type="checkbox" name="PERSONALBEST[1][over_all_publish]" value="1"> <span>Tick this box and everyone will see your PB!</span></label></div>
                                                </div>

                                            <div class="remove hide btn btnred">DELETE</div>

                                        </div>

                                   </div>
                                </div> 
                                <div id="append_data" class="append_data"></div>
                                 <div class="clear"></div>
                                <div class="bottom-Button">
                                    <div><!--<div class="add_more_section"> <a href="javascript:void(0)" class="add_more
                                    btn btnred">ADD NEW</a> </div></div> -->
                                    <div><input type="submit" value="SUBMIT PB" class="submit_PB"></div>
                                </div>
                        </form>
                        </div>
                    
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>