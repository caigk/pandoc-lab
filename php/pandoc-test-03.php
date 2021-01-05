<?php
/**
 * PANDOC 生成pdf,与word测试
 *
 * Php version 7.0.0
 *
 * @category My
 * @package  My
 * @author   蔡 <caigk@weihesoft.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://
 */



$fields = array(
    '跟投人姓名' => "梁**",
    '跟投人身份证号' => '6101031976022####X',
    '跟投人联系地址' => '上海浦东新区',
    '跟投人电子邮件' => 'ca###@zldcgroup.com',
    '跟投人联系电话' => '135855****1',
    '跟投人传真号码' => '02153*****7',
    '合伙企业名称' => "苏州工业园区纽新房地产开发企业（有限合伙）",
    '合伙企业统一信用号码' => '320594000201609090206',
    '合伙企业代表人' => '王卫祥',
    '合伙企业联系地址' => '苏州工业园区苏雅路 318 号明天翔国际大厦 1 幢 1501 室',
    '合伙企业银行账户' => '苏州工业园区纽新房地产开发企业（有限合伙）',
    '合伙企业银行账号' => '32250199743600000776',
    '合伙企业开户银行' => '建行相城支行',
    '配资平台公司名称' => "锦艺****公司",
    '配资平台公司号码' => '61010319760229203X',
    '配资平台公司代表人' => '习进平',
    '借款平台公司联系地址' => '上海浦东新区',
    '跟投项目名称' => "张家港地块",
    '跟投项目公司' => '张家港市锦艺轩置业有限公司',
    '跟投立项日期' => '2017年01月01日',
    '跟投配资利率' => '10%',
    'pAutoRepaymentDate' => "2018年01月01日",
    '跟投金额' => 372.00,
    '跟投金额大写' => '',
    '跟投股比' => '3.123%',
    '跟投配资比例' => '1:4',
    '跟投本金' => "123.00",
    '跟投本金大写' => '',
    '跟投配资金额' => 249.00,
    '跟投配资金额大写' => '',
    '合同编号' => 'GT-2016-02-001',
    '合同签定日期' => '2017年03月01日',
    '股东类型' => "跟略性股东",
    '企业名称' => '锦艺*****'
);


/**
 * 依据模板及变量生成pdf
 *
 * @param array  $vars         变量集
 * @param string $templatePath 模板路径
 *
 * @return void
 */
function generatePdf($vars, $templatePath)
{
    $outfilename = pathinfo(__FILE__, PATHINFO_FILENAME);
    $outdir = __DIR__.'/../out/';

    $descriptorspec = array(
    0 => array("pipe", "r"),  // 标准输入，子进程从此管道中读取数据
    1 => array("pipe", "w"),  // 标准输出，子进程向此管道中写入数据
    2 => array("file", "{$outdir}{$outfilename}-error1.txt", "a") // 标准错误，写入到一个文件
    );

    $env = array(
        'PATH' => '/usr/local/bin/:/Library/TeX/texbin'
    );


    $command = "pandoc --template {$templatePath} --pdf-engine=xelatex -t pdf";

    $process = proc_open(
        $command,
        $descriptorspec,
        $pipes,
        $outdir,
        $env
    );

    if (is_resource($process)) {
        $varsWithYaml = "---\n".implode(
            "\n",
            array_map(
                function ($key, $value) {
                    return $key.' : '.$value;
                },
                array_keys($vars),
                array_values($vars)
            )
        )."\n...";

        fwrite($pipes[0], $varsWithYaml);
        fclose($pipes[0]);
        
        $outFile = fopen("{$outdir}{$outfilename}.pdf", 'w');
        stream_copy_to_stream($pipes[1], $outFile);
        fclose($pipes[1]);
        fclose($outFile);

        // 切记：在调用 proc_close 之前关闭所有的管道以避免死锁。
        $return_value = proc_close($process);

        echo "command returned $return_value\n";
    }
}


/**
 * 依据模板及变量生成docx
 *
 * @param array  $vars         变量集
 * @param string $templatePath 模板路径
 *
 * @return void
 */
function generateDocs(array $vars, string $templatePath)
{
    //因为生成docx时不支持模板
    //注意分为两步
    // 1.先生成latex
    // 2.使用latex生成docx


    $outfilename = pathinfo(__FILE__, PATHINFO_FILENAME);
    $outdir = __DIR__.'/../out/';

    $descriptorspec = array(
    0 => array("pipe", "r"),  // 标准输入，子进程从此管道中读取数据
    1 => array("pipe", "w"),  // 标准输出，子进程向此管道中写入数据
    2 => array("file", "{$outdir}{$outfilename}-error2.txt", "a") // 标准错误，写入到一个文件
    );

    $env = array(
        'PATH' => '/usr/local/bin/:/Library/TeX/texbin'
    );

    $command = "pandoc --template {$templatePath} --pdf-engine=xelatex -t latex";

    $process = proc_open(
        $command,
        $descriptorspec,
        $pipes,
        $outdir,
        $env
    );

    echo $command."\n";
    if (is_resource($process)) {
        $varsWithYaml = "---\n".implode(
            "\n",
            array_map(
                function ($key, $value) {
                    return $key.' : '.$value;
                },
                array_keys($vars),
                array_values($vars)
            )
        )."\n...";

        fwrite($pipes[0], $varsWithYaml);
        fclose($pipes[0]);
        
        $process1 = proc_open(
            "pandoc -f latex -t docx --reference-doc={$outdir}../sample/custom-reference.docx",
            $descriptorspec,
            $pipes1,
            $outdir,
            $env
        );
        echo "pandoc -f latex -t docx --reference-doc=reference.docx\n";
        if (is_resource($process1)) {
            stream_copy_to_stream($pipes[1], $pipes1[0]);
            fclose($pipes[1]);
            fclose($pipes1[0]);

            $outFile = fopen("{$outdir}{$outfilename}.docx", 'w');
            stream_copy_to_stream($pipes1[1], $outFile);
            fclose($pipes1[1]);
            fclose($outFile);

            $return_value1 = proc_close($process1);
            echo "command1 returned $return_value1\n";
        }

        // 切记：在调用 proc_close 之前关闭所有的管道以避免死锁。
        $return_value = proc_close($process);

        echo "command returned $return_value\n";
    }
}

generatePdf($fields, __DIR__."/../sample/pandocTemplate.tex");
generateDocs($fields,  __DIR__."/../sample/pandocTemplate.tex");
