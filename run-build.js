import cron from 'node-cron';
import { exec } from 'child_process';

// Ejecutar el comando 'npm run build' cada 3 segundos
cron.schedule('*/5 * * * * *', () => { // Cada 5 segundos
    console.log('Ejecutando "npm run build"...');
    exec('npm run build', (error, stdout, stderr) => {
        if (error) {
            console.error(`Error ejecutando "npm run build": ${error.message}`);
            return;
        }
        if (stderr) {
            console.error(`stderr: ${stderr}`);
            return;
        }
        console.log(`stdout: ${stdout}`);
    });
});

console.log('Cron job iniciado: se ejecutará "npm run build" cada 5 segundos.');
