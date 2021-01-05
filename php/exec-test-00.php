<?php

/**
 * 测试 调用系统命令
 *
 * Php version 7.0.0
 *
 * @category My
 * @package  My
 * @author   蔡 <caigk@weihesoft.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://
 */

$command = "ls ~"; //ls是linux下的查目录，文件的命令

exec($command, $array); //执行命令

print_r($array);
