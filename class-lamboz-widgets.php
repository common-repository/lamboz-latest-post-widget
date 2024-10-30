<?php
if ( ! class_exists( 'LambozLatestPostWidgets' ) ) {
	class LambozLatestPostWidgets extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'llpw_widget',
				__( 'Lamboz Latest Post Widget', 'lamboz-latest-post-widgets' ),
				array(
					'customize_selective_refresh' => true,
				)
			);
		}
		public function form( $instance ) {
			$defaults = array(
				'llpw_post_type'   => '',
				'llpw_is_image' => '',
				'llpw_post_date_applied' => '',
				'llpw_post_limit' => '',
				
			);
			extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'lamboz-latest-post-widgets' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'llpw_post_type' ) ); ?>"><?php _e( 'Post Type:', 'lamboz-latest-post-widgets' ); ?></label>
				<?php
				$llpw_post_types = get_post_types();
				?>
				<select name="<?php echo $this->get_field_name( 'llpw_post_type' ); ?>" id="<?php echo $this->get_field_id( 'llpw_post_type' ); ?>" class="widefat">
					<?php
					$options = array();
					foreach($llpw_post_types as $post_types)
					{
						echo '<option value="' . esc_attr( $post_types ) . '" id="' . esc_attr( $post_types ) . '" '. selected( $llpw_post_type, $post_types, false ) . '>'. $post_types . '</option>';
					}

					?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'llpw_post_limit' ) ); ?>"><?php _e( 'Post Limit:', 'lamboz-latest-post-widgets' ); ?></label>

				<select name="<?php echo $this->get_field_name( 'llpw_post_limit' ); ?>" id="<?php echo $this->get_field_id( 'llpw_post_limit' ); ?>" class="widefat">
					<?php

					for($i=1; $i<=10; $i++)
					{
						echo '<option value="' . esc_attr( $i ) . '" id="' . esc_attr( $llpw_post_limit.'_'.$i) . '" '. selected( $llpw_post_limit, $i, false ) . '>'. $i . '</option>';
					}
					?>
				</select>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'llpw_is_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'llpw_is_image' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $llpw_is_image ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'llpw_is_image' ) ); ?>"><?php _e( 'Display Featured Image', 'lamboz-latest-post-widgets' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'llpw_post_date_applied' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'llpw_post_date_applied' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $llpw_post_date_applied ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'llpw_post_date_applied' ) ); ?>"><?php _e( 'Display Published Date', 'lamboz-latest-post-widgets' ); ?></label>
			</p>
			<?php 
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
			$instance['llpw_post_type']   = isset( $new_instance['llpw_post_type'] ) ? wp_strip_all_tags( $new_instance['llpw_post_type'] ) : '';
			$instance['llpw_post_limit'] =  isset( $new_instance['llpw_post_limit'] ) ? wp_strip_all_tags( $new_instance['llpw_post_limit'] ) : '';
			$instance['llpw_is_image'] = isset( $new_instance['llpw_is_image'] ) ? 1 : false;
			$instance['llpw_post_date_applied'] = isset( $new_instance['llpw_post_date_applied'] ) ? 1 : false;
			return $instance;
		}
	// Display the widget
		public function widget( $args, $instance ) {
			extract( $args );
			$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$llpw_post_type   = isset( $instance['llpw_post_type'] ) ? $instance['llpw_post_type'] : '';
			$llpw_post_limit   = isset( $instance['llpw_post_limit'] ) ? $instance['llpw_post_limit'] : '';
			$llpw_is_image = ! empty( $instance['llpw_is_image'] ) ? $instance['llpw_is_image'] : false;
			$llpw_post_date_applied = ! empty( $instance['llpw_post_date_applied'] ) ? $instance['llpw_post_date_applied'] : false;
			echo $before_widget;
			echo '<div class="widget-text wp_widget_plugin_box">';
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			if($llpw_post_limit)
			{
				$llpw_post_limit = $llpw_post_limit;
			}else{
				$llpw_post_limit = 5;
			}	
			if ( $llpw_post_type !="" ){
				?>
				<ul class="lamboz_latest_post">
					<?php
					$llpw_post_args = array(  
						'post_type' => $llpw_post_type, 
						'posts_per_page' => $llpw_post_limit,
						'orderby' => 'desc',
					);
					$llpw_post_loop = new WP_Query( $llpw_post_args );
					while ( $llpw_post_loop->have_posts() ) : $llpw_post_loop->the_post(); 
						?>
						<li class="">
							<?php
							if($llpw_is_image == 1)
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
												if($llpw_post_date_applied == 1){
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
									if($llpw_post_date_applied == 1){
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
}
// Register the widget
if (!function_exists('llpw_custom_widget'))
{
	function llpw_custom_widget(){
		register_widget('LambozLatestPostWidgets');
	}
}

add_action( 'widgets_init', 'llpw_custom_widget' );