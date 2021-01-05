const { spawn } = require('child_process');
const { readFile } = require('fs');

var cmd = spawn(
	'pandoc',
	[
		'--pdf-engine=xelatex',
		//'-t ' + outFormat,
		'--template=sample/pandocTemplate.tex',
		//"--pdf-engine-opt=-output-directory=out",
		'--output=out/pandoc-jstest-01.pdf'
	]
);


cmd.stdout.on('data', function (data) {
	console.log('stdout:' + data);

});

cmd.stderr.on('data', function (data) {
	console.log('stderr:' + data);
});

readFile("sample/vars.yaml",function(err,data){
	cmd.stdin.write(data);
	cmd.stdin.end();
});


cmd.on('exit', function (code) {
	console.log('child process exit with code ' + code);
});