<?php

class Math
{

    public static function isOdd($number)
    {
		return ($number % 2 != 0); //return true once $number/2 returns a remainder
     
    }


    public static function isEven($number)
    {
        return ($number % 2 == 0); //return true once $number/2 does not return a remainder

    }

    public static function isFibonacci($number)
    {
        // return true if number is in fibonacci series false if not
		
		
		if($number == 0) //0 is in the fibonacci sequence so return true
			return true;
			else //otherwise assign 1 to the first member of the fibonacci array
			$fibonacci[0] = 1;
		
		
		
		for ($i = 1; $i <= $number; $i++)
		{
			/*if i equals 1, assign one as the second member of the array, otherwise assign current member 
			the sum of the two previous members.
			*/
			$i == 1 ? $fibonacci[$i] = 1 : $fibonacci[$i] = $fibonacci[$i-1] + $fibonacci[$i-2];
			
			if($number == $fibonacci[$i]) //return true if $number falls in that fibonacci sequence
			return true;
		}
		
		return false;
    }

}


