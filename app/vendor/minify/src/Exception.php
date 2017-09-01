<?php


/**
 * @deprecated Use Exceptions\BasicException instead
 *
 * @author Matthias Mullie <minify@mullie.eu>
 */
if( !class_exists( 'Exception' ) ) {
    abstract class Exception extends \Exception
    {
    }
}
