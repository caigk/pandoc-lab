<?php
/**
 * 测试 latex命令
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

$env = array(
    'PATH' => '/usr/local/bin/:/Library/TeX/texbin'
);

$process = proc_open(
    "xelatex -output-directory=out", //
    $descriptorspec,
    $pipes,
    $outdir,
    $env
);

if (is_resource($process)) {
    // $pipes 现在看起来是这样的：
    // 0 => 可以向子进程标准输入写入的句柄
    // 1 => 可以从子进程标准输出读取的句柄
    // 错误输出将被追加到文件 /tmp/error-output.txt

    //$in = file_get_contents('latex01.tex'); //从文件读
    $in = <<<'TEX'
\documentclass[12pt, letterpaper, twoside]{article}
\usepackage[utf8]{inputenc}

\begin{document}

\begin{abstract}
This is a simple paragraph at the beginning of the document. A brief introduction to the main subject.
\end{abstract}

In this document some extra packages and parameters
were added. There is an encoding package,
and pagesize and fontsize parameters.

This line will start a second paragraph. And I can
    break\\ the lines \\ and continue on a new line.

\end{document}
TEX;

    fwrite($pipes[0], $in);
    fclose($pipes[0]);

    // $outFile = fopen('latex01.txt', 'w');
    // stream_copy_to_stream($pipes[1], $outFile);
    // fclose($pipes[1]);
    // fclose($outFile);

    echo stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    // 切记：在调用 proc_close 之前关闭所有的管道以避免死锁。
    $return_value = proc_close($process);

    echo "command returned $return_value\n";
}
