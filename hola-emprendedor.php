<?php
/**
 * Plugin Name: Hola Emprendedor
 * Plugin URI:  https://github.com/brandkover/hola-emprendedor/
 * Description: A simple and inspiring add-on for entrepreneurs. Displays a random motivational quote at the top of your admin panel.
 * Version:     1.2.0
 * Author:      Brandkover, Uber Chuquimia
 * Author URI:  https://brandkover.com
 * License:     GPL v2 or later
 * Text Domain: hola-emprendedor
 */

namespace HolaEmprendedor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const VERSION = '1.2.0';

class Core {

	private static $instance = null;

	/**
	 * Get the plugin singleton instance.
	 *
	 * @return Core
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_notices', [ $this, 'print_quote' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
	}

	/**
	 * Print a random quote in the WordPress admin area.
	 *
	 * @return void
	 */
	public function print_quote() {
		if ( ! current_user_can( 'read' ) ) {
			return;
		}

		$quote = $this->get_random_quote();
		$dir   = is_rtl() ? 'rtl' : 'ltr';

		printf(
			'<div id="hola-emprendedor" dir="%s">%s</div>',
			esc_attr( $dir ),
			esc_html( $quote )
		);
	}

	/**
	 * Register and print the inline admin styles.
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		$css = '
			#hola-emprendedor {
				float: right;
				padding: 5px 15px 0 0;
				margin: 0;
				font-size: 13px;
				font-style: italic;
				color: #72777c;
				line-height: 1.6;
			}

			@media screen and (max-width: 782px) {
				#hola-emprendedor {
					float: none;
					display: block;
					width: 100%;
					text-align: center;
					padding: 12px 0;
					margin-bottom: 5px;
					clear: both;
				}
			}
		';

		wp_register_style( 'hola-emprendedor-style', false, [], VERSION );
		wp_enqueue_style( 'hola-emprendedor-style' );
		wp_add_inline_style( 'hola-emprendedor-style', $css );
	}

	/**
	 * Get a random quote from the configured quote list.
	 *
	 * @return string
	 */
	private function get_random_quote() {
		$quotes = [
			'Failure is simply the opportunity to begin again, this time more intelligently. — Henry Ford',
			'I have not failed. I\'ve just found 10,000 ways that won\'t work. — Thomas Edison',
			'Your time is limited, so don\'t waste it living someone else\'s life. — Steve Jobs',
			'The best way to predict the future is to create it. — Peter Drucker',
			'If you are not embarrassed by the first version of your product, you\'ve launched too late. — Reid Hoffman',
			'The biggest risk is not taking any risk. — Mark Zuckerberg',
			'Work hard in silence, let success be your noise. — Frank Ocean',
			'Motivation is what gets you started. Habit is what keeps you going. — Jim Ryun',
			'Don\'t find customers for your products, find products for your customers. — Seth Godin',
			'Do it with passion or not at all. — Rosa Nouchette Carey',
			'Persistence is very important. You should not give up unless you are forced to give up. — Elon Musk',
		];

		/**
		 * Filters the list of motivational quotes shown in the admin area.
		 *
		 * @param string[] $quotes List of quotes.
		 */
		$quotes = apply_filters( 'hola_emprendedor_quotes', $quotes );
		$quotes = array_values( array_filter( array_map( 'trim', (array) $quotes ) ) );

		if ( empty( $quotes ) ) {
			return '';
		}

		return wptexturize( $quotes[ wp_rand( 0, count( $quotes ) - 1 ) ] );
	}
}

Core::get_instance();
