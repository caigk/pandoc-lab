const { spawn } = require('child_process');

var cmd = spawn('pandoc', ['-v']);

cmd.stdout.on('data', function (data) {
  console.log('stdout:' + data);
});

cmd.stderr.on('data', function (data) {
  console.log('stderr:' + data);
});

cmd.on('exit', function (code) {
  console.log('child process exit with code ' + code);
});