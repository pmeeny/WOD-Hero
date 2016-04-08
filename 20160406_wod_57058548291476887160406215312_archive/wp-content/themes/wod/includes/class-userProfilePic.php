<?php
class UserProfileImage_Upload
{
    public $upload_overrides = '';


    public function __construct(){
        $this->upload_overrides = array('test_form' => false,'unique_filename_callback' => array($this,'userPic_rename_filename') );
        add_action('wp_ajax_uploadUserPic',array($this,'uploadUserProfilePic'));
        add_action('wp_ajax_deleteUserPic',array($this,'delete_file'));
        //add_filter('wp_handle_upload_prefilter', array($this,'userProfile_pre_upload'), 2);
        //add_filter('wp_handle_upload', array($this,'userProfile_post_upload'), 2);
    }


    public function userPic_rename_filename($dir, $name, $ext){
        return $name.$ext;
    }



    // Change the upload path to the one we want
    function userProfile_pre_upload($file){
        add_filter('upload_dir', array($this,'userProfile_upload_dir'));
        return $file;
    }

    // Change the upload path back to the one Wordpress uses by default
    function userProfile_post_upload($fileinfo){
        remove_filter('upload_dir', array($this,'userProfile_upload_dir'));
        return $fileinfo;
    }

    function userProfile_upload_dir($path){
        /*
        * Determines if uploading from inside a post/page/cpt - if not, default Upload folder is used
        */
        if( !empty( $path['error'] ) )
            return $path; //error or uploading not from a post/page/cpt
        /*
        * Save uploads in SLUG based folders
        */
        $customdir = '/userProfile';
        $path['path']    = str_replace($path['subdir'], '', $path['path']); //remove default subdir (year/month)
        $path['url']     = str_replace($path['subdir'], '', $path['url']);
        $path['subdir']  = $customdir;
        $path['path']   .= $customdir;
        $path['url']    .= $customdir;
        return $path;
    }

    /*
    *  User Profile Image Upload
    * */
    function handle_file($upload_data){
        $return = false;
        $upload_overrides = $this->upload_overrides;

        $uploaded_file = wp_handle_upload($upload_data, $upload_overrides);

        if (isset($uploaded_file['file'])) {
            $file_loc = $uploaded_file['file'];
            $file_name = basename($upload_data['name']);
            $file_type = wp_check_filetype($file_name);

            $attachment = array(
                'post_mime_type' => $file_type['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $attach_id = get_user_meta(get_current_user_id(), 'profile_picture', true);
            if(!empty($attach_id)){
                $this->deleteExistingImg($attach_id);
            }

            $attach_id = wp_insert_attachment($attachment, $file_loc);
            $attach_data = wp_generate_attachment_metadata($attach_id, $file_loc);
            wp_update_attachment_metadata($attach_id, $attach_data);

            update_user_meta(get_current_user_id(),'profile_picture',$attach_id);

            $return = array('data' => $attach_data, 'id' => $attach_id);

            return $return;
        }
        return $return;
    }


    function getHTML($attachment)
    {
        $attach_id = $attachment['id'];
        $file = explode('/', $attachment['data']['file']);
        $file = array_slice($file, 0, count($file) - 1);
        $path = implode('/', $file);
        $image = $attachment['data']['sizes']['thumbnail']['file'];
        $post = get_post($attach_id);
        $dir = wp_upload_dir();
        $path = $dir['baseurl'] . '/' . $path;

        $html = '';
        //$html .= '<li class="aaiu-uploaded-files">';
        $html .= sprintf('<div style="margin-bottom:7px;"><img src="%s" name="' . $post->post_title . '" /></div>', $path . '/' . $image);
        $html .= sprintf('<a href="javascript:void(0);" class="action-delete btn btn-success btn-sm" onclick="deleteUserPic(this)" data-upload_id="%d">%s</a></span>', $attach_id, __('Delete'));
        //$html .= sprintf('<input type="hidden" name="aaiu_image_id[]" value="%d" />', $attach_id);
        //$html .= '</li>';

        return $html;
    }

    function uploadUserProfilePic(){
        $response = array();

        if(!isset($_FILES['userPic']) || !is_uploaded_file($_FILES['userPic']['tmp_name'])){
            respond_by_json(true,'Image file is Missing!','error','','');
        }

        if(!empty($_FILES['userPic']['name'])){
            $uploaded_file_type = $_FILES["userPic"]["type"]; // Get image type
            $allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png'); // allowed image formats
            if(in_array($uploaded_file_type, $allowed_file_types)) // validate image format with the given formats
            {
                $file = array(
                    'name' => $_FILES['userPic']['name'],
                    'type' => $_FILES['userPic']['type'],
                    'tmp_name' => $_FILES['userPic']['tmp_name'],
                    'error' => $_FILES['userPic']['error'],
                    'size' => $_FILES['userPic']['size']
                );
                $attachment = $this->handle_file($file);

                if (is_array($attachment)) {
                    $html = $this->getHTML($attachment);
                    $response = array(
                        'success' => true,
                        'html' => $html,
                    );
                    echo json_encode($response);
                    exit;
                }
                respond_by_json(true,'Image not uploaded','error','','');
                exit;
            }
            else{
                respond_by_json(true,'Invaliad Image Format','error','','');
            }
        }
    }

    public function deleteExistingImg($attach_id=''){
        wp_delete_attachment($attach_id, true);
        update_user_meta(get_current_user_id(),'profile_picture','');
    }

    public function delete_file($attach_id='')
    {
        $attach_id = $_POST['attach_id'];
        wp_delete_attachment($attach_id, true);
        update_user_meta(get_current_user_id(),'profile_picture','');
        $html = '';
        //$html .= sprintf('<img height="150" width="150" src="%s" />', get_bloginfo('template_url').'/images/user.png');
        //$html .= sprintf('<br /><a href="javascript:void(0);" id="editProfilePic">%s</a></span>' ,__('Edit'));
        $html .= sprintf('<div id="upload">%s</a></div>' ,__('upload Photo'));
        $response = array(
            'success' => true,
            'html' => $html,
        );
        echo json_encode($response);
        exit;
    }
}
$userImageUpload = new UserProfileImage_Upload();
?>