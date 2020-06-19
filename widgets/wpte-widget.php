<?php
/**
 * WooCommerce Product Table Addon
 *
 * @since 1.0.0
 */
class WPTE_WIDGET_CLASS extends \Elementor\Widget_Base {


	public function get_name() {
		return 'wpte_addon';
	}


	public function get_title() {
		return __( 'WPTE ADDON', 'wpte-addon' );
	}

	public function get_icon() {
		return 'eicon-table-of-contents';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'woo', 'woocommerce', 'wpte', 'table', 'product' ];
	}

    public function get_script_depends() {
        return [ 'wpte-addon' ];
    }

    public function get_style_depends() {
        return [ 'wpte-addon' ];
    }

	private function get_wooproduct_list(){

		$productarr = array();

		$args = [
		    'status'    => 'publish',
		    'orderby' => 'name',
		    'order'   => 'ASC',
		    'limit' => -1,
		];

		$all_products = wc_get_products($args);

		foreach ($all_products as $key => $product) {
			$pid = $product->get_id();
		    $productarr[$pid] = $product->get_title() . " (id:{$pid})";
		}

		return $productarr;
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'wpte-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'select_product',
			[
				'label' => __( 'Select Product', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'label_block' => true,
				'options' => $this->get_wooproduct_list(),

			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		global $post, $product, $woocommerce;

		$settings = $this->get_settings_for_display();
		$select_product = $settings['select_product'];

		$wrapper_classes = "";
		$align = $settings['align'];
		if( $align ) {
			$wrapper_classes .= " align-{$align} ";
		}

		$align_tablet = $settings['align_tablet'];
		if( $align_tablet ) {
			$wrapper_classes .= " align_tablet-{$align_tablet} ";
		}

		$align_mobile = $settings['align_mobile'];
		if( $align_mobile ) {
			$wrapper_classes .= " align_mobile-{$align_mobile} ";
		}

		//echo "<pre>";
		//print_r( $settings );
		//echo "</pre>";

		if( !$select_product ){
			return;
		}

		add_action( 'wcqv_product_data', 'woocommerce_template_single_add_to_cart');

		$product_id = (int) $select_product;

	    $wiqv_loop = new WP_Query(
	        array(
	            'post_type' => 'product',
	            'p' => $product_id,
	        )
	    );

		if( $wiqv_loop->have_posts() ) : ?>
			<div class="wpte-product-wrap-outer <?php _e( $wrapper_classes ); ?>">
				<div class="wpte-product-wrap">
					<?php while ( $wiqv_loop->have_posts() ) : $wiqv_loop->the_post(); ?>
						<div class="wpte-title"><?php the_title(); ?></div>
						<?php do_action( 'wcqv_product_data' );
				 	endwhile;
				 	wp_reset_postdata(); ?>
				 </div>
			 </div>
		<?php endif; ?>

		<?php

	}

}