<?php 

	
	add_shortcode( 'show_physio_category', 'show_physio_category_func' );
	function show_physio_category_func($atts){
		global $wpdb;
		$atts = shortcode_atts(
		array(
			'id' => '',
			'link'=> ''
		), $atts, 'show_physio_category' );
		$tbl_name = $wpdb->prefix . 'term_taxonomy';
	    if( $atts['id'] == '' ){
	    	$catItems = $wpdb->get_results($wpdb->prepare(
			  	"SELECT * FROM ". $tbl_name ." WHERE taxonomy = %s", 
			  	'store_locator_category'
			  ));
	    }else{
	    	$catItems = explode(',' , $atts['id']);
	    }
	    ob_start();
?>
		<div class="container physio-category <?= ($atts['id'] !== '') ? 'multiple-items' : '' ; ?>">
    		<?php if( $atts['id'] == '' ): ?>
    			<div class="row">
    		<?php endif; ?>

			<?php if($catItems): ?>
				<?php foreach ($catItems as $catItem): 

					if( $atts['id'] == '' ){
						$catId = $catItem->term_id;
					}else{
						$catId = $catItem;
					}

					$image_id = get_term_meta ( $catId, 'store_locator_category-image-id', true );
					$catInfo = get_term_by( 'id', $catId, 'store_locator_category' );
					$imgUrl = '';
					?>
					<?php if ( $image_id ): 
			           $imgUrl = wp_get_attachment_image_src( $image_id, 'full' ); 
						?>
			    	<?php endif; ?>
			    			<?php if( $atts['id'] == '' ): ?>
				    			<div class="col-md-3 col-sm-6">
				    		<?php endif; ?>
  								<div class="text-center pb-3">
  									<div class="card mx-2">
                    <a href="<?= home_url(); ?>/search-physiotherapist?speciality_id=<?= $catInfo->term_id; ?>"></a>
                      <input type="hidden" class="physio_id" value="<?= $catInfo->term_id; ?>">
  									  <img class="card-img-top" src="<?= ( !empty( $imgUrl[0] ) ) ? $imgUrl[0] : 'https://dummyimage.com/360x360/000/fff'; ?>" alt="">
  									  <div class="card-body">
  									    <h3 class="mb-2 pt-2 card-text"><?php echo $catInfo->name ?></h3>				  
  									  </div>
  									</div>
  								</div>
                
							<?php if( $atts['id'] == '' ): ?>
				    			</div>
				    		<?php endif; ?>
				<?php endforeach; ?>
			    		<?php endif; ?>
				<?php if( $atts['id'] == '' ): ?>
	    			</div>
			<?php endif; ?>
		</div>
			<?php if( $atts['link'] !== '' ): ?>
				<div class="text-center pb-3">
					<a href="<?= $atts['link']; ?>" class="btn btn-primary">Show More</a>
				</div>
			<?php endif; ?>
<?php	
		return ob_get_clean();
	}

	/**
 * Plugin class
 **/
if ( ! class_exists( 'STORE_CAT_IMG' ) ) {

class STORE_CAT_IMG {

  public function __construct() {
    //
  }
 
 /*
  * Initialize the class and start calling our hooks and filters
  * @since 1.0.0
 */
 public function init() {
   add_action( 'store_locator_category_add_form_fields', array ( $this, 'add_store_locator_category_image' ), 10, 2 );
   add_action( 'created_store_locator_category', array ( $this, 'save_store_locator_category_image' ), 10, 2 );
   add_action( 'store_locator_category_edit_form_fields', array ( $this, 'update_store_locator_category_image' ), 10, 2 );
   add_action( 'edited_store_locator_category', array ( $this, 'updated_store_locator_category_image' ), 10, 2 );
   add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
   add_action( 'admin_footer', array ( $this, 'add_script' ) );
 }

public function load_media() {
 wp_enqueue_media();
}
 
 /*
  * Add a form field in the new store_locator_category page
  * @since 1.0.0
 */
 public function add_store_locator_category_image ( $taxonomy ) { ?>
   <div class="form-field term-group">
     <label for="store_locator_category-image-id"><?php _e('Image', 'hero-theme'); ?></label>
     <input type="hidden" id="store_locator_category-image-id" name="store_locator_category-image-id" class="custom_media_url" value="">
     <div id="store_locator_category-image-wrapper"></div>
     <p>
       <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'hero-theme' ); ?>" />
       <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
    </p>
   </div>
 <?php
 }
 
 /*
  * Save the form field
  * @since 1.0.0
 */
 public function save_store_locator_category_image ( $term_id, $tt_id ) {
   if( isset( $_POST['store_locator_category-image-id'] ) && '' !== $_POST['store_locator_category-image-id'] ){
     $image = $_POST['store_locator_category-image-id'];
     add_term_meta( $term_id, 'store_locator_category-image-id', $image, true );
   }
 }
 
 /*
  * Edit the form field
  * @since 1.0.0
 */
 public function update_store_locator_category_image ( $term, $taxonomy ) { ?>
   <tr class="form-field term-group-wrap">
     <th scope="row">
       <label for="store_locator_category-image-id"><?php _e( 'Image', 'hero-theme' ); ?></label>
     </th>
     <td>
       <?php $image_id = get_term_meta ( $term -> term_id, 'store_locator_category-image-id', true ); ?>
       <input type="hidden" id="store_locator_category-image-id" name="store_locator_category-image-id" value="<?php echo $image_id; ?>">
       <div id="store_locator_category-image-wrapper">
         <?php if ( $image_id ) { ?>
           <?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
         <?php } ?>
       </div>
       <p>
         <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'hero-theme' ); ?>" />
         <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
       </p>
     </td>
   </tr>
 <?php
 }

/*
 * Update the form field value
 * @since 1.0.0
 */
 public function updated_store_locator_category_image ( $term_id, $tt_id ) {
   if( isset( $_POST['store_locator_category-image-id'] ) && '' !== $_POST['store_locator_category-image-id'] ){
     $image = $_POST['store_locator_category-image-id'];
     update_term_meta ( $term_id, 'store_locator_category-image-id', $image );
   } else {
     update_term_meta ( $term_id, 'store_locator_category-image-id', '' );
   }
 }

/*
 * Add script
 * @since 1.0.0
 */
 public function add_script() { ?>
   <script>
     jQuery(document).ready( function($) {
       function ct_media_upload(button_class) {
         var _custom_media = true,
         _orig_send_attachment = wp.media.editor.send.attachment;
         $('body').on('click', button_class, function(e) {
           var button_id = '#'+$(this).attr('id');
           var send_attachment_bkp = wp.media.editor.send.attachment;
           var button = $(button_id);
           _custom_media = true;
           wp.media.editor.send.attachment = function(props, attachment){
             if ( _custom_media ) {
               $('#store_locator_category-image-id').val(attachment.id);
               $('#store_locator_category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
               $('#store_locator_category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
             } else {
               return _orig_send_attachment.apply( button_id, [props, attachment] );
             }
            }
         wp.media.editor.open(button);
         return false;
       });
     }
     ct_media_upload('.ct_tax_media_button.button'); 
     $('body').on('click','.ct_tax_media_remove',function(){
       $('#store_locator_category-image-id').val('');
       $('#store_locator_category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
     });
     // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-store_locator_category-ajax-response
     $(document).ajaxComplete(function(event, xhr, settings) {
       var queryStringArr = settings.data.split('&');
       if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
         var xml = xhr.responseXML;
         $response = $(xml).find('term_id').text();
         if($response!=""){
           // Clear the thumb image
           $('#store_locator_category-image-wrapper').html('');
         }
       }
     });
   });
 </script>
 <?php }

  }
 
$STORE_CAT_IMG = new STORE_CAT_IMG();
$STORE_CAT_IMG -> init();
 
}

add_action('wp_footer', 'category_info_modal_in_footer_func');
  function category_info_modal_in_footer_func(){
        global $post;
    
   
    if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'show_physio_category') ) {

        echo '
              <!-- Modal -->
          <div class="modal fade" id="category_location" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalScrollableTitle">Physiotherapist Location</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="input-group input-group">
                      <input type="text" class="form-control" id="locator_physio" name="locator_physio" value="" placeholder="City">
                      <div class="input-group-append">
                      <input type="hidden" class="physio_id">
                          <a href=""><button class="btn btn-primary" type="submit">Submit</button></a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          ';
    }
  }

 ?>