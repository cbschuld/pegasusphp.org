<?php

/**
 * Replaces all white-space characters with the given string
 * <pre>
 *  * value : the text to process
 *  * with : the replacement string, note that any number of consecutive white-space characters will be replaced by a single replacement string
 * </pre>
 * Example :
 *
 * <code>
 * {"a    b  c		d
 *
 * e"|whitespace}
 *
 * results in : a b c d e
 * </code>
 *
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 *
 * This file is released under the LGPL
 * "GNU Lesser General Public License"
 * More information can be found here:
 * {@link http://www.gnu.org/copyleft/lesser.html}
 *
 * @author     Jordi Boggiano <j.boggiano@seld.be>
 * @copyright  Copyright (c) 2008, Jordi Boggiano
 * @license    http://www.gnu.org/copyleft/lesser.html  GNU Lesser General Public License
 * @link       http://dwoo.org/
 * @version    0.9.1
 * @date       2008-05-30
 * @package    Dwoo
 */
function Dwoo_Plugin_whitespace_compile(Dwoo_Compiler $compiler, $value, $with=' ')
{
	return "preg_replace('#\s+#'.(strcasecmp(\$this->charset, 'utf-8')===0?'u':''), $with, $value)";
}
