<?php
/**
 * Display the QA Feild
 *
 * @package     QA_Captcha
 * @subpackage  QA_Captcha/includes
 * @copyright   Copyright (c) 2014, Jason Witt
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 * @author      Jason Witt <contact@jawittdesigns.com>
 */

class QA_Captcha_Init {

	/**
	 * The array of questions
	 * 
	 * @var array
	 */
	public $array;

	/**
	 * The Class constructor
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->array = get_option( 'qac_options' );
		add_action( 'init', array( $this, 'load_filters'), 1);
		add_filter( 'shake_error_codes', array( $this, 'add_shake_error_codes' ), 1);
		add_action( 'register_form', array( $this, 'display_field' ) );
	}

	/**
	 * Load the filters
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function load_filters() {
		if( get_option( 'qac_options' ) ) {
			add_filter( 'registration_errors', array( $this, 'authenticate_answer' ), 10, 3 );
		}
	}

	/**
	 * Randomize the array of questions
	 *
	 * @since  1.0.0
	 * @access private
	 * @return array the randomized list of questions
	 */
	private function randomize_option() {
		$array = $this->array['qac_repeat_group'];
		$selection = '';
		if( is_array( $array ) ) {
			$selection = array_rand( $array, 1 );
		}
		return $array[$selection];
	}

	/**
	 * Display the question and answer field on the registration form
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function display_field() {
		$option = $this->randomize_option();
		$value = strtolower(trim( $option['qac_answer'] ) );
		?>
			<p>
				<label for="qac_option"><?php echo stripslashes_deep($option['qac_question']); ?></br>
					<input type="text" size="20" value="" id="qac_question" name="qac_question">
				</label>
			</p>
		<?php
	}

	/**
	 * Authenticate the question form field
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  obj $user     the user variable
	 * @param  string $username the username
	 * @param  string $password the password
	 * @return obj           the user object
	 */
	public function authenticate_answer( $errors ) {
		$error = get_option( 'qac_error' );
		$option = $this->randomize_option();
		$value = strtolower( trim( $option['qac_answer'] ) );
		if( empty( $_POST['qac_question'] ) || strtolower( trim( $_POST['qac_question'] ) ) !== $value ) {
			$errors->add( 'qac-error', $this->error_message() );
			
		}
		return $errors;
	}

	/**
	 * Custom error message 
	 *
	 * @since  1.0.0
	 * @access private
	 * @return string the custom error message
	 */
	private function error_message() {
		$message = __('<strong>ERROR</strong>: Incorrect answer.' );
		return $message;
	}

	/**
	 * Add the custom error to the shake error code function
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $codes the array of code for the shake error code function
	 * @return array the error code with the custom error code
	 */
	public function add_shake_error_codes($codes) {
		$codes[] = 'qac-error';
		return $codes;
	}
}