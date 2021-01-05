
import { exec } from 'child_process';
const child = exec('termux-battery-status', function (error, stdout, stderr) {
	if (error == null) {
		//const result = JSON.parse(stdout);
		console.log(stdout);
	}

});
