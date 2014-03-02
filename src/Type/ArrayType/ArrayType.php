<?php

namespace Consistence\Type\ArrayType;

use Closure;

class ArrayType extends \Consistence\ObjectPrototype
{

	const STRICT_TRUE = true;
	const STRICT_FALSE = false;

	final public function __construct()
	{
		throw new \Consistence\StaticClassException();
	}

	/**
	 * Wrapper for PHP in_array, provides safer default parameter
	 *
	 * @param mixed[] $haystack
	 * @param mixed $needle
	 * @param boolean $strict
	 * @return boolean
	 */
	public static function inArray(array $haystack, $needle, $strict = self::STRICT_TRUE)
	{
		return in_array($needle, $haystack, $strict);
	}

	/**
	 * Wrapper for PHP array_search, provides safer default parameter. Returns null when value is not found.
	 *
	 * @param mixed[] $haystack
	 * @param mixed $needle
	 * @param boolean $strict
	 * @return integer|string|null
	 */
	public static function findKey(array $haystack, $needle, $strict = self::STRICT_TRUE)
	{
		$result = array_search($needle, $haystack, $strict);
		if ($result === false) {
			return null;
		}

		return $result;
	}

	/**
	 * @param mixed[] $haystack
	 * @param mixed $needle
	 * @param boolean $strict
	 * @return integer|string
	 */
	public static function getKey(array $haystack, $needle, $strict = self::STRICT_TRUE)
	{
		$result = static::findKey($haystack, $needle, $strict);
		if ($result === null) {
			throw new \Consistence\Type\ArrayType\ElementDoesNotExistException();
		}

		return $result;
	}

	/**
	 * @param mixed[] $haystack
	 * @param integer|string $key
	 * @return mixed|null
	 */
	public static function findValue(array $haystack, $key)
	{
		if (!array_key_exists($key, $haystack)) {
			return null;
		}

		return $haystack[$key];
	}

	/**
	 * @param mixed[] $haystack
	 * @param integer|string $key
	 * @return mixed
	 */
	public static function getValue(array $haystack, $key)
	{
		$result = static::findValue($haystack, $key);
		if ($result === null) {
			throw new \Consistence\Type\ArrayType\ElementDoesNotExistException();
		}

		return $result;
	}

	/**
	 * Stops on first occurrence when callback(\Consistence\Type\ArrayType\KeyValuePair) is trueish or returns null
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return \Consistence\Type\ArrayType\KeyValuePair|null
	 */
	public static function findByCallback(array $haystack, Closure $callback)
	{
		$keyValuePair = new KeyValuePairMutable(0, 0);
		foreach ($haystack as $key => $value) {
			$keyValuePair->setPair($key, $value);
			if ($callback($keyValuePair)) { // not strict comparison to be consistent with array_filter behavior
				return new KeyValuePair($key, $value);
			}
		}

		return null;
	}

	/**
	 * Stops on first occurrence when callback(\Consistence\Type\ArrayType\KeyValuePair) is trueish or throws exception
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return \Consistence\Type\ArrayType\KeyValuePair
	 */
	public static function getByCallback(array $haystack, Closure $callback)
	{
		$result = static::findByCallback($haystack, $callback);
		if ($result === null) {
			throw new \Consistence\Type\ArrayType\ElementDoesNotExistException();
		}

		return $result;
	}

}
