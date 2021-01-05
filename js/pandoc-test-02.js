const { spawn } = require('child_process');
const { readFile } = require('fs');

var cmdTex = spawn(
	'pandoc',
	[
		'--pdf-engine=xelatex',
		'--to=latex',
		'--template=sample/pandocTemplate.tex',
		//"--pdf-engine-opt=-output-directory=out",
		//'--output=out/pandoc-jstest-02.tex'
	]
);


var cmdDocx = spawn(
	'pandoc',
	[
		'--from=latex',
		'--to=docx',
		'--reference-doc=sample/custom-reference.docx',
		'--output=out/pandoc-jstest-02.docx'
	]
);

cmdTex.stdout.on('data', function (data) {
	cmdDocx.stdin.write(data);
});

cmdTex.stderr.on('data', function (data) {
	console.log('stderr:' + data);
});

cmdDocx.stderr.on('data', function (data) {
	console.log('stderr1:' + data);
});

readFile("sample/vars.yaml",function(err,data){
	cmdTex.stdin.write(data);
	cmdTex.stdin.end();
});


cmdTex.on('exit', function (code) {
	console.log('child process exit with code ' + code);
	cmdDocx.stdin.end();
});