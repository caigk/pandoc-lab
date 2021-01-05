const { stdout } = require('process');
// spawn

const { stringify } = require('querystring');

var cmd = require('child_process').spawn('termux-battery-status');

cmd.stdout.on('data',function(data){
	const result = JSON.parse(data);
/*
{
  health: 'GOOD',
  percentage: 100,
  plugged: 'UNPLUGGED',
  status: 'DISCHARGING',
  temperature: 21,
  current: -152
}
*/
	console.log(`helth:${result.health}`);
});


cmd.stderr.on('data',function(data){
	console.log('stderr:'+data);
});


cmd.on('exit',function(code){
	console.log('child process exit with code '+code);
});