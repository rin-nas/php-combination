<?php
/**
 * Combinatorics. Combinations enumeration algorithm.
 *
 * Features
 *   * Combination::вinary() — enumeration of all possible placements
 *     of K units in N bits, returns an indexed array of integers.
 *   * Combination::couple() — returns a matrix with unique permutations
 *     in the form of two-dimensional array (K = 2).
 *   * Combination::all() — returns all combinations of array elements
 *     to each other (K = N).
 *
 * @link     http://code.google.com/p/php-combination/
 * @license  http://creativecommons.org/licenses/by-sa/3.0/
 * @author   Nasibullin Rinat
 * @version  1.0.1
 */
class Combination
{
	#запрещаем создание экземпляра класса, вызов методов этого класса только статически!
	private function __construct() {}

	//Операция сдвига вправо самой младшей единицы, с неприкосновенностью более старших разрядов.
	private static function _shiftLast1($a)
	{
		return (($a - 1) ^ (($a ^ ($a - 1)) >> 2));
	}

	//Операция дописывания единицы справа от самой младшей единицы.
	private static function _add1AfterLast1($a) {
		return ($a | ((($a ^ ($a - 1)) + 1) >> 2));
	}

	//Метод генерации первой комбинации. K единиц сдвигаются вправо на (N-K) позиций.
	private static function _first($k, $n) {
		return ( ((1 << $k) - 1) << ($n - $k) );
	}

	//Метод для вычисления следующей комбинации
	private static function _next($a)
	{
		// в случае последней комбинации вернём ноль
		if (($a & ($a + 1)) == 0) return 0;

		//($a & 1) -- операция определения значения младшего разряда
		if ($a & 1) return self::_add1AfterLast1(self::_next($a >> 1) << 1);
		return self::_shiftLast1($a);
	}

	/**
	 * The main method: enumeration of all possible placements of K units in N bits
	 * 
	 * Methods for special cases (the methods are more convenient that accepts an array):
	 *     If K = N, see self::all()
	 *	   If K = 2, see self::couple()
	 *
	 * Example
	 *   All possible variants placement of 3 units in 5 bits:
	 *   1 1 1 0 0
	 *   1 1 0 1 0
	 *   1 1 0 0 1
	 *   1 0 1 1 0
	 *   1 0 1 0 1
	 *   1 0 0 1 1
	 *   0 1 1 1 0
	 *   0 1 1 0 1
	 *   0 1 0 1 1
	 *   0 0 1 1 1
	 *
	 * @link    http://www.quizful.net/post/fast-combinations-enumerate-algorithm  Source
	 * @link    http://graphics.stanford.edu/~seander/bithacks.html  Bit Twiddling Hacks
	 * @see     self::all()
	 * @see     self::couple()
	 * @param   int    $k
	 * @param   int    $n
	 * @return  array     An indexed array of integers
	 */
	public static function вinary($k, $n)
	{
		$a = array();
		$i = self::_first($k, $n);
		do $a[] = $i;
		while ($i = self::_next($i));
		return $a;
	}

	/**
	Returns a matrix with unique permutations in the form of two-dimensional array.
	For the number of elements N, number of combinations will be (N - 1) * N / 2.
	The classical problem of shaking hands with 2 teams of players.
	Example for N = 4, the intersection matrix is as follows:
		  0 1 2 3
		0 - + + +
		1 - - + +
		2 - - - +
		3 - - - -
	And for input array(0, 1, 2, 3) returns:
	array(
		0 => array(1, 0),
		1 => array(2, 0),
		2 => array(3, 0),
		3 => array(2, 1),
		4 => array(3, 1),
		5 => array(3, 2),
	)
	*
	* @param   array  $items  An indexed array
	* @return  array          Two-dimensional indexed array
	*/
	public static function couple(array $items)
	{
		$a = array();
		for ($i = 0, $n = count($items); $i < $n; $i++)
			for ($j = $i + 1; $j < $n; $j++)
				$a[] = array($items[$j], $items[$i]);
		return $a;
	}

	/**
	 * Returns all combinations of array elements to each other.
	 * For the number of elements N, number of combinations will be (2 ^ N) - 1.
	 *
	 * @param   array  $items  An indexed array
	 * @return  array          Two-dimensional indexed array
	 */
	public static function all(array $items)
	{
		$a = array();
		for ($i = 1, $с = count($items), $max = 1 << $с; $i < $max; $i++)
		{
			$next = array();
			for ($b = 0; $b < $с; $b++) if ($i & (1 << $b)) $next[] = $items[$b];
			$a[] = $next;
		}
		return $a;
	}

	//TODO
	public static function tests()
	{
		//$s = str_pad(decbin($i), $n, '0', STR_PAD_LEFT);
		//d($s);
	}

}