<?php
/*
Plugin Name: Botobor
Plugin URI: https://github.com/mekras/botobor-wordpress
Description: Protect forms from spam.
Version: 1.0
Author: Михаил Красильников
Author URI: https://github.com/mekras
Text Domain: botobor
Domain Path: /languages
License: GPL3
*/

/*  © Copyright 2017  Михаил Красильников

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists( 'WPBotobor' ) ) {

	require_once __DIR__ . '/vendor/botobor.php';

	/**
	 * Главный класс модуля.
	 */
	class WPBotobor {
		/**
		 * Инициализирует экземпляр.
		 */
		public function __construct() {
			add_action( 'plugins_loaded', [ $this, 'onLoad' ] );
			add_action( 'comment_form_before', [ $this, 'onFormStart' ] );
			add_action( 'comment_form_after', [ $this, 'onFormEnd' ] );
			add_action( 'pre_comment_on_post', [ $this, 'onComment' ] );

			Botobor_Keeper::get()->handleRequest();
		}

		/**
		 * Загружает файлы локализации.
		 */
		public function onLoad() {
			load_plugin_textdomain( 'botobor', false, basename( __DIR__ ) . '/languages/' );
		}

		/**
		 * Включает перехват выводимой формы.
		 */
		public function onFormStart() {
			ob_start();
		}

		/**
		 * Завершает вывод формы.
		 */
		public function onFormEnd() {
			$html  = ob_get_clean();
			$bForm = new Botobor_Form( $html );
			echo $bForm->getCode();
		}

		/**
		 * Проверяет комментарий.
		 */
		public function onComment() {
			if ( Botobor_Keeper::get()->isRobot() ) {
				wp_die( __( 'botobor_message_check_failed', 'botobor' ),
					__( 'Comment Submission Failure' ),
					array( 'back_link' => true ) );
			}
		}
	}

	$wpBotobor = new WPBotobor();
}