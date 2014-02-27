<?php

namespace Consistence;

class PhpException extends \Exception
{

	/**
	 * @param string|null $message
	 * @param \Exception|null $previous
	 */
	public function __construct($message = null, \Exception $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}

}
