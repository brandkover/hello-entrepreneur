<?php
/**
 * Plugin Name: Hola Emprendedor
 * Plugin URI:  https://brandkover.com/hola-emprendedor
 * Description: Un plugin simple e inspirador para emprendedores. Muestra una frase motivacional aleatoria en la parte superior de tu panel de administración.
 * Version:     1.1.0
 * Author:      Brandkover
 * Author URI:  https://brandkover.com
 * License:     GPL v2 or later
 * Text Domain: hola-emprendedor
 */

namespace HolaEmprendedor;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Core {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_notices', [ $this, 'print_quote' ] );
		add_action( 'admin_head', [ $this, 'inject_styles' ] );
	}

	public function print_quote() {
		$quote = $this->get_random_quote();
		$lang  = get_locale();
		$dir   = ( in_array( $lang, [ 'ar', 'he', 'fa' ], true ) ) ? 'rtl' : 'ltr';

		printf(
			'<div id="hola-emprendedor" dir="%s">%s</div>',
			esc_attr( $dir ),
			esc_html( $quote )
		);
	}

	public function inject_styles() {
		if ( ! current_user_can( 'read' ) ) {
			return;
		}
		?>
		<style>
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
					float: none !important;
					display: block;
					width: 100%;
					text-align: center;
					padding: 12px 0;
					margin-bottom: 5px;
					height: auto;
					clear: both;
				}
			}
		</style>
		<?php
	}

	private function get_random_quote() {
		$lyrics = "El fracaso es solo la oportunidad de comenzar de nuevo de forma más inteligente. — Henry Ford
No he fracasado. He encontrado 10.000 formas que no funcionan. — Thomas Edison
Tu tiempo es limitado, así que no lo desperdicies viviendo la vida de alguien más. — Steve Jobs
La mejor forma de predecir el futuro es crearlo. — Peter Drucker
Si no te avergüenza la primera versión de tu producto, lo lanzaste muy tarde. — Reid Hoffman
El riesgo más grande es no tomar ninguno. — Mark Zuckerberg
Trabaja duro en silencio, que el éxito sea tu ruido. — Frank Ocean
La motivación es lo que te pone en marcha, el hábito es lo que hace que sigas. — Jim Ryun
No busques clientes para tus productos, busca productos para tus clientes. — Seth Godin
Hazlo con pasión o no lo hagas. — Rosa Nouchette Carey
La persistencia es muy importante. No debes rendirte a menos que te veas obligado a rendirte. — Elon Musk";

		// Split by new line
		$lines = explode( "\n", $lyrics );

		return wptexturize( $lines[ mt_rand( 0, count( $lines ) - 1 ) ] );
	}
}

// Init
Core::get_instance();
