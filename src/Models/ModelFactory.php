<?php

namespace Saltus\WP\Framework\Models;

class ModelFactory {

	protected $fields_service;

	public function __construct( $fields_service ) {
		$this->fields_service = $fields_service;
	}

	/**
	 * Route to class
	 */
	public function create( $config ) {

		if ( ! $config->has( 'type' ) ) {
			return false;
		}

		if ( in_array( $config->get( 'type' ), [ 'post-type', 'cpt', 'posttype', 'post_type' ], true ) ) {
			$cpt = new PostType( $config );
			$cpt->setup();
			if ( $config->has( 'meta' ) || $config->has( 'settings' ) ) {
				$fields = $this->fields_service->get_new();
				$fields->setup( $cpt->name, $config->get( 'meta' ), $config->get( 'settings' ) );

				add_action( 'cmb2_admin_init', array( $fields, 'init' ), 0 );
			}
			return $cpt;

		}
		if ( in_array( $config->get( 'type' ), [ 'taxonomy', 'tax', 'category', 'cat', 'tag' ], true ) ) {
			$taxonomy = new Taxonomy( $config );
			$taxonomy->setup();
			return $taxonomy;
		}

		return false;

	}
}
