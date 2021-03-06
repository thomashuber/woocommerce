<?php
/**
 * Data Store Tests
 * @package WooCommerce\Tests\Product
 * @since 2.7.0
 */
class WC_Tests_Data_Store extends WC_Unit_Test_Case {

	/**
	 * Make sure WC_Data_Store returns an exception if we try to load a data
	 * store that doesn't exist.
	 *
	 * @since 2.7.0
	 */
	function test_invalid_store_throws_exception() {
		try {
			$product_store = new WC_Data_Store( 'bogus' );
		} catch ( Exception $e ) {
			$this->assertEquals( $e->getMessage(), 'Invalid data store.' );
			return;
		}
		$this->fail( 'Invalid data store exception not correctly raised.' );
	}

	/**
	 * Make sure ::load returns null if an invalid store is found.
	 *
	 * @since 2.7.0
	 */
	function test_invalid_store_load_returns_null() {
		$product_store = WC_Data_Store::load( 'product-test' );
		$this->assertNull( $product_store );
	}

	/**
	 * Make sure we can swap out stores.
	 *
	 * @since 2.7.0
	 */
	function test_store_swap() {
		$this->load_dummy_store();

		$store = new WC_Data_Store( 'dummy' );
		$this->assertEquals( 'WC_Dummy_Data_Store_CPT', $store->get_current_class_name() );

		add_filter( 'woocommerce_dummy_data_store', array( $this, 'set_dummy_store' ) );

		$store = new WC_Data_Store( 'dummy' );
		$this->assertEquals( 'WC_Dummy_Data_Store_Custom_Table', $store->get_current_class_name() );

		add_filter( 'woocommerce_dummy_data_store', array( $this, 'set_default_dummy_store' ) );
	}

	/**
	 * Test to see if `first_second ``-> returns to `first` if unregistered.
	 *
	 * @since 2.7.0
	 */
	function test_store_sub_type() {
		$this->load_dummy_store();
		$store = WC_Data_Store::load( 'dummy_sub' );
		$this->assertEquals( 'WC_Dummy_Data_Store_CPT', $store->get_current_class_name() );
	}

	/* Helper Functions. */

	/**
	 * Loads two dummy data store classes that can be swapt out for each other. Adds to the `woocommerce_data_stores` filter.
	 *
	 * @since 2.7.0
	 */
	function load_dummy_store() {
		include_once( dirname( dirname( dirname( __FILE__ ) ) ) . '/framework/class-wc-dummy-data-store.php' );
		add_filter( 'woocommerce_data_stores', array( $this, 'add_dummy_data_store' ) );
	}

	/**
	 * Adds a default class for the 'dummy' data store.
	 *
	 * @since 2.7.0
	 */
	function add_dummy_data_store( $stores ) {
		$stores['dummy'] = 'WC_Dummy_Data_Store_CPT';
		return $stores;
	}

	/**
	 * Helper function/filter to swap out the default dummy store for a different one.
	 *
	 * @since 2.7.0
	 */
	function set_dummy_store( $store ) {
		return 'WC_Dummy_Data_Store_Custom_Table';
	}

	/**
	 * Helper function/filter to swap out the 'dummy' store for the default one.
	 *
	 * @since 2.7.0
	 */
	function set_default_product_store( $store ) {
		return 'WC_Dummy_Data_Store_CPT';
	}
}
