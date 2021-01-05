const { stdout } = require('process');
// spawn

const { stringify } = require('querystring');

var exec = require('child_process').exec;
const child = exec('termux-battery-status',function(error,stdout,stderr){
	if(error == null)
	{
		const result = JSON.parse(stdout);
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
	}

});
