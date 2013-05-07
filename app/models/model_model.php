<?php
/**
 * The WP Models Model
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author authtoken
 * @version 0.1
 * @since 0.1
 */
if( ! class_exists( WP_Models_Model ) ):
	/**
	 * The WP Models Model Object
	 *
	 * @package pkgtoken
	 * @subpackage subtoken
	 * @since 
	 */
	class WP_Models_Model
	{
		/**
	 	 * The model post id
	 	 *
	 	 * @package pkgtoken
	 	 * @subpackage subtoken
	 	 * @since 
	 	 * @var string
	 	 */
	 	private $ID;
	 	
	 	/**
	 	 * The model's age.
	 	 *
	 	 * @package pkgtoken
	 	 * @subpackage subtoken
	 	 * @since 
	 	 * @var string
	 	 */
	 	private $age;
	 	
	 	/**
	 	 * The model's sign
	 	 *
	 	 * @package pkgtoken
	 	 * @subpackage subtoken
	 	 * @since 
	 	 * @var
	 	 */
	 	private $sign;
	 	
	 	public function __construct()
	 	{
	 	}

		public function get_age()
		{
			return $this->age;
		}
		
		public function get_sign()
		{
			return $this->sign;
		}
	}
endif;
?>