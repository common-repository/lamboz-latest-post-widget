<?php
class LambozLatestPostWidgets extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'my_custom_widget',
			__( 'Lamboz Latest Post Widget', 'lamboz-latest-post-widgets' ),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}
	public function form( $instance ) {
		$defaults = array(
			'lamboz_post_type'   => '',
			'lamboz_is_image' => '',
			'lamboz_is_date' => '',
			'title'    => '',
			'text'     => '',
		);
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'lamboz-latest-post-widgets' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'lamboz_post_type' ) ); ?>"><?php _e( 'Post Type:', 'lamboz-latest-post-widgets' ); ?></label>
			<?php
			$lamboz_post_types = get_post_types();
			?>
			<select name="<?php echo $this->get_field_name( 'lamboz_post_type' ); ?>" id="<?php echo $this->get_field_id( 'lamboz_post_type' ); ?>" class="widefat">
				<?php
				$options = array();
				foreach($lamboz_post_types as $post_types)
				{
					echo '<option value="' . esc_attr( $post_types ) . '" id="' . esc_attr( $post_types ) . '" '. selected( $lamboz_post_type, $post_types, false ) . '>'. $post_types . '</option>';
				}

				?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'lamboz_post_limit' ) ); ?>"><?php _e( 'Post Limit:', 'lamboz-latest-post-widgets' ); ?></label>
			
			<select name="<?php echo $this->get_field_name( 'lamboz_post_limit' ); ?>" id="<?php echo $this->get_field_id( 'lamboz_post_limit' ); ?>" class="widefat">
				<?php
				
				for($i=1; $i<=10; $i++)
				{
					echo '<option value="' . esc_attr( $i ) . '" id="' . esc_attr( $lamboz_post_limit.'_'.$i) . '" '. selected( $lamboz_post_limit, $i, false ) . '>'. $i . '</option>';
				}
				?>
			</select>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'lamboz_is_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'lamboz_is_image' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $lamboz_is_image ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'lamboz_is_image' ) ); ?>"><?php _e( 'Display Featured Image', 'lamboz-latest-post-widgets' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'lamboz_is_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'lamboz_is_date' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $lamboz_is_date ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'lamboz_is_date' ) ); ?>"><?php _e( 'Display Published Date', 'lamboz-latest-post-widgets' ); ?></label>
		</p>
		<?php 
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['lamboz_post_type']   = isset( $new_instance['lamboz_post_type'] ) ? wp_strip_all_tags( $new_instance['lamboz_post_type'] ) : '';
		$instance['lamboz_post_limit'] =  isset( $new_instance['lamboz_post_limit'] ) ? wp_strip_all_tags( $new_instance['lamboz_post_limit'] ) : '';
		$instance['lamboz_is_image'] = isset( $new_instance['lamboz_is_image'] ) ? 1 : false;
		$instance['lamboz_is_date'] = isset( $new_instance['lamboz_is_date'] ) ? 1 : false;
	// $instance['text']     = isset( $new_instance['text'] ) ? wp_strip_all_tags( $new_instance['text'] ) : '';
	// $instance['textarea'] = isset( $new_instance['textarea'] ) ? wp_kses_post( $new_instance['textarea'] ) : '';
		return $instance;
	}
	// Display the widget
	public function widget( $args, $instance ) {
		extract( $args );
		$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$lamboz_post_type   = isset( $instance['lamboz_post_type'] ) ? $instance['lamboz_post_type'] : '';
		$lamboz_post_limit   = isset( $instance['lamboz_post_limit'] ) ? $instance['lamboz_post_limit'] : '';
		$lamboz_is_image = ! empty( $instance['lamboz_is_image'] ) ? $instance['lamboz_is_image'] : false;
		$lamboz_is_date = ! empty( $instance['lamboz_is_date'] ) ? $instance['lamboz_is_date'] : false;
		// $text     = isset( $instance['text'] ) ? $instance['text'] : '';
		// $textarea = isset( $instance['textarea'] ) ?$instance['textarea'] : '';
		// WordPress core before_widget hook (always include )
		echo $before_widget;
		echo '<div class="widget-text wp_widget_plugin_box">';
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		if($lamboz_post_limit)
		{
			$lamboz_post_limit = $lamboz_post_limit;
		}else{
			$lamboz_post_limit = 5;
		}	
		if ( $lamboz_post_type !="" ){
			?>
			<ul class="lamboz_latest_post">
				<?php
				$lamboz_latest_post_args = array(  
					'post_type' => $lamboz_post_type, 
					'posts_per_page' => $lamboz_post_limit,
					'orderby' => 'desc',
				);
				$lamboz_loop = new WP_Query( $lamboz_latest_post_args );
				while ( $lamboz_loop->have_posts() ) : $lamboz_loop->the_post(); 
					?>
					<li class="">
						<?php
						if($lamboz_is_image == 1)
						{
							$thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'thumbnail', true );
							$thumbnail_url = $thumbnail_url[0];

							if ( $thumbnail_url !="") { ?>
								<div class="lamboz_latest_featured">
									<a href="<?php the_permalink();?>" class="lamboz_latest_post_title" style="text-decoration: none !important;">
										<div class="lamboz_latest_featured_image">
											<img src= "<?php echo $thumbnail_url ; ?>" alt="<?php the_title(); ?>" >
										</div >
										<div class="lamboz_latest_featured_title">
											<?php the_title(); ?>
											<?php 
											if($lamboz_is_date == 1){
												?>
												<small class="lamboz_latest_post_post_date"><br/><?php echo get_the_date(); ?></small>
												<?php	
											}
											?>
										</div>

									</a>
								</div>
								<?php 
							}
						}else{ ?>

							<div>
								<a href="<?php the_permalink();?>" class="lamboz_latest_post_title" style="text-decoration: none !important;"><?php the_title(); ?></a>
								<?php 
								if($lamboz_is_date == 1){
									?>
									<small class="lamboz_latest_post_post_date"><br/><?php echo get_the_date(); ?></small>
									<?php	
								}
								?>
							</div>
							<?php 
						}
						?>
					</li>
					<?php
				endwhile; 
				wp_reset_query();
				?>
			</ul>
			<?php 
		}
		echo '</div>';
		echo $after_widget;
	}
}
// Register the widget
function my_register_custom_widget(){
	register_widget('LambozLatestPostWidgets');
}
add_action( 'widgets_init', 'my_register_custom_widget' );