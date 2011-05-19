<?php defined('SYSPATH') or die('No direct script access.');

class Template_Default extends Template {

	/**
	 * Default driver works with .php files.
	 *
	 * @var     string
	 * @access  protected
	 * @see     Template::$_extension
	 */
	protected $_extension = 'php';
	
	/**
	 * Template directory
	 *
	 * @access  protected
	 */
	protected $_dir = 'views';

	/**
	 * Captures the output that is generated when a view is included.
	 * The view data will be extracted to make local variables. This method
	 * is static to prevent object scope resolution.
	 *
	 *     $output = Template_Default::capture($file, $data);
	 *
	 * @param   string     filename
	 * @param   ViewModel
	 * @return  string
	 */
	protected static function capture($file, ViewModel $view)
	{
		if (get_class($view) === 'ViewModel')
		{
			// This class is plain view model, extract
			// as an array
			extract((array) $view, EXTR_SKIP);
		}

		// Capture the view output
		ob_start();

		try
		{
			// Load the view within the current scope
			include $file;
		}
		catch (Exception $e)
		{
			// Delete the output buffer
			ob_end_clean();

			// Re-throw the exception
			throw $e;
		}

		// Get the captured output and close the buffer
		return ob_get_clean();
	}

	/**
	 * The rendering method, wooo!
	 *
	 * @param   ViewModel  Data to be passed to template
	 * @return  string     Rendered template
	 * @uses    Template_Default::capture
	 * @uses    Template::set_filename
	 */
	public function render(ViewModel $view)
	{
		return self::capture($this->get_filename($view), $view);
	}

}