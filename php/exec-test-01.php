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


$outfilename = pathinfo(__FILE__, PATHINFO_FILENAME);
$outdir = __DIR__.'/../out/';

$descriptorspec = array(
   0 => array("pipe", "r"),  // 标准输入，子进程从此管道中读取数据
   1 => array("pipe", "w"),  // 标准输出，子进程向此管道中写入数据
   2 => array("file", "{$outdir}{$outfilename}-error.txt", "a") // 标准错误，写入到一个文件
);

$cwd = '.';
$env = array(
    'some_option' => 'aeiou')
    ;

$process = proc_open('php', $descriptorspec, $pipes, $cwd, $env);

if (is_resource($process)) {
    // $pipes 现在看起来是这样的：
    // 0 => 可以向子进程标准输入写入的句柄
    // 1 => 可以从子进程标准输出读取的句柄
    // 错误输出将被追加到文件 /tmp/error-output.txt

    fwrite($pipes[0], '<?php phpinfo() ?>');
    fclose($pipes[0]);

    echo stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    

    // 切记：在调用 proc_close 之前关闭所有的管道以避免死锁。
    $return_value = proc_close($process);

    echo "command returned $return_value\n";
}
