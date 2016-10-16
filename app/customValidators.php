<?php
/**
 * FALSE = validation error
 * TRUE = validation passed
 */

Validator::extend('full_name', function ($attribute, $value, $parameters)
{
    if (preg_match("/^[a-z ,.'-]+$/i", $value))
    {
        return true;
    }

    return false;

});

Validator::extend('password', function ($attribute, $value, $parameters)
{
    $r1 = '/[A-Z]/'; //Uppercase
    $r2 = '/[a-z]/'; //lowercase
    $r3 = '/[!@#$%()\-_=+:,.]/'; // special chars
    $r4 = '/[0-9]/'; //numbers

    if (strlen($value) > 14 || strlen($value) < 4)
    {
        return false;
    }

    else
    {
        if (preg_match_all($r1, $value) < 1)
        {
            return true;
        } // Uppercase
        if (preg_match_all($r2, $value) < 1)
        {
            return true;
        } // lowercase
        if (preg_match_all($r3, $value) < 1)
        {
            return true;
        } // special chars
        if (preg_match_all($r4, $value) < 1)
        {
            return true;
        } // numbers
    }

    return false;

});

Validator::extend('phone', function ($attribute, $value, $parameters)
{
    if (ctype_digit($value) && strlen($value) == 10)
    {
        return true;
    }

    return false;

});

Validator::extend('alpha_space_dash', function ($attribute, $value, $parameters)
{
    if (!preg_match("/^([-a-z_ ])+$/i", $value))
    {
        return false;
    }

    return true;

});

Validator::extend('majors', function ($attribute, $value, $parameters)
{
    if (!preg_match("/^([-a-z_\/.& ])+$/i", $value))
    {
        return false;
    }

    return true;

});

Validator::extend('alpha_space_dash_num', function ($attribute, $value, $parameters)
{
    if (!preg_match("/^([-a-z0-9_ ])+$/i", $value))
    {
        return false;
    }

    return true;

});

Validator::extend('integer_array', function ($attribute, $value, $parameters)
{
    foreach ($value as $int)
    {
        if (count($parameters) > 0)
        {
            if (strlen((string)$int) != $parameters[0] && !ctype_digit($int))
            {
                return false;
            }
        }
        else
        {
            if (!ctype_digit($int))
            {
                return false;
            }
        }
    }

    return true;

});

Validator::extend('studentid', function ($attribute, $value, $parameters)
{
    if (strlen($value) !== 9)
    {
        return false;
    }

    if (strtolower($value[0]) !== 'a')
    {
        return false;
    }

    if (!ctype_digit(substr($value, 1, 8)))
    {
        return false;
    }

    return true;

});

Validator::extend('over', function ($attribute, $value, $parameters)
{
    if (Input::get($attribute) > $parameters[0])
    {
        if (Input::get($parameters[1]) == NULL || Input::get($parameters[1]) == '')
        {
            return false;
        }

        return true;
    }

    return true;

});

Validator::extend('address', function ($attribute, $value, $parameters)
{
    if (!preg_match("/^([-a-z0-9 _|.-])+$/i", $value))
    {
        return false;
    }

    return true;

});

Validator::extend('text', function ($attribute, $value, $parameters)
{
    if (preg_match("/^[*<>@#^+={}~]+$/i", $value))
    {
        return false;
    }

    return true;

});

Validator::extend('decimal', function ($attribute, $value, $parameters)
{
    if (preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $value))
    {
        return true;
    }

    return false;

});

Validator::extend('gpa', function ($attribute, $value, $parameters)
{
    //Must be 4 characters
    if (strlen($value) != 4)
    {
        return false;
    }

    else
    {
        // first character is a number and no greater than 4
        if ($value[0] > 4 || !ctype_digit($value[0]))
        {
            return false;
        }

        //Have a decimal after the first [0]
        if ($value[1] != '.')
        {
            return false;
        }


        if (!ctype_digit($value[2]) && !ctype_digit($value[3]))
        {
            return false;
        }
    }

    return true;

});

Validator::extend('essay', function($attribute, $value, $parameters)
{
    if (preg_match("/^[*<>@#^+={}~]+$/i", $value))
    {
        return false;
    }

    return true;
});

Validator::extend('words', function ($attribute, $value, $parameters)
{
    if (str_word_count($value) < $parameters[0])
    {
        return false;
    }

    return true;
});

Validator::extend('array_num', function ($attribute, $value, $parameters)
{
    if (is_array($value))
    {
        foreach ($value as $v)
        {
            if (!ctype_digit($v))
            {
                return false;
            }

            return true;
        }
    }

    return false;
});

Validator::extend('array_text', function ($attribute, $value, $parameters)
{
    foreach ($value as $v)
    {
        if ($v != '')
        {
            if (preg_match("/^[*<>@#^+={}~]+$/i", $v))
            {
                return false;
            }
        }
    }
    
    return true;
});

Validator::extend('rank', function ($attribute, $value, $parameters)
{
    if (strlen($value) <= 6)
    {
        if (!preg_match("/^[*?(),.;:'\"\\|\/<>@#$`][^+={}~]+$/i", $value))
        {
            return true;
        }

        return false;
    }

    return false;
});

Validator::extend('Required_if_in_array_digit', function ($attribute, $value, $parameters)
{
    if (in_array($parameters[1], Input::get($attribute)))
    {
        if (Input::get($parameters[0]) == '' || Input::get($parameters[0]) == NULL)
        {
            return false;
        }

        else
        {
            foreach (Input::get($parameters[0]) as $v)
            {
                if (ctype_digit($v))
                {
                    return true;
                }

                else
                {
                    return false;
                }
            }
        }
    }

    else
    {
        return true;
    }
});

Validator::extend('array_fundCode', function($attribute, $value, $parameters)
{
    if ($value[0] != '')
    {
        foreach ($value as $v)
        {     
            if (!ctype_alpha($v[0]))
            {
                return false;
            }
            else
            {
                if (strlen($v) != 4)
                {
                    return false;
                }
                else
                {
                    if (! ctype_digit(substr($v, 1, 3)))
                    {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    return false;
});

Validator::extend('array_studentID', function($attribute, $value, $parameters)
{
    if ($value[0] != '')
    {
        foreach ($value as $v)
        {
            if (strlen($v) !== 9)
            {
                return false;
            }

            if (strtolower($v[0]) !== 'a')
            {
                return false;
            }

            if (!ctype_digit(substr($v, 1, 8)))
            {
                return false;
            }
        }

        return true;
    }

    return false;
});

Validator::extend('array_awardAmount', function($attribute, $value, $parameters)
{
    if ($value[0] != '')
    {
        foreach ($value as $v)
        {
            if (preg_match("/\\\$?((\d{1,3}(,\d{3})*)|(\d+))(\.\d{2})?$/", $v))
            {
                return true;
            }
        }

        return false;
    }

    return false;
});

Validator::extend('fund', function($attribute, $value, $parameters)
{   
    if (!ctype_alpha($value[0]))
    {
        return false;
    }
    else
    {
        if (strlen($value) != 4)
        {
            return false;
        }
        else
        {
            if (! ctype_digit(substr($value, 1, 3)))
            {
                return false;
            }

            return true;    
        }
    }
});