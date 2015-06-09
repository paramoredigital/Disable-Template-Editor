<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Disable Template Editor Extension
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		Jesse Bunch
 * @link		http://getbunch.com/
 */

class Disable_template_editor_ext
{
	public $settings 		= array();
	public $description		= 'Disables the EE template editor for users to control templates via source control. This prevents folks from creating out of sync issues between the server and repo.';
	public $docs_url		= 'http://getbunch.com/';
	public $name			= 'Disable Template Editor';
	public $settings_exist	= 'n';
	public $version			= '1.0.4';

	private $EE;

	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = '')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
	}

	// ----------------------------------------------------------------------

	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see http://codeigniter.com/user_guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	public function activate_extension()
	{
		// Setup custom settings in this array.
		$this->settings = array();

		$data = array(
			'class'		=> __CLASS__,
			'method'	=> 'inject_cp_js',
			'hook'		=> 'cp_js_end',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);

		$this->EE->db->insert('extensions', $data);
	}

	// ----------------------------------------------------------------------

	/**
	 * Extension Hook
	 *
	 * Returns the functionality to the extension output hook.
	 *
	 * @return string
	 */
	public function inject_cp_js()
	{
		$str = '$(function() {' .
			'$("#action_nav").hide();' .
			'$("#action_nav .toggle_editor").click();' .
			'$("#templateEditor input[name=\"update_and_return\"]").parent().hide();' .
			'$("#templateEditor #template_details > p").html("<b>Read Only</b> (Source Controlled)");' .
			'$("#templateEditor textarea[name=\"template_data\"]").attr("readonly", "readonly");' .
			'$("form .editAccordion").hide();
			$("input[name=save_template_file]").hide();
			$("label[for=save_template_file]").hide();' .
		'});';

		return !$this->EE->extensions->last_call ? $str : $this->EE->extensions->last_call . $str;
	}

	// ----------------------------------------------------------------------

	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}

	// ----------------------------------------------------------------------

	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
	}

	// ----------------------------------------------------------------------
}

/* End of file ext.disable_template_editor.php */
/* Location: /system/expressionengine/third_party/disable_template_editor/ext.disable_template_editor.php */
