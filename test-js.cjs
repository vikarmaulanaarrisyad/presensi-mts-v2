const fs = require('fs');

const content = fs.readFileSync('c:\\laragon\\www\\presensi-mts\\resources\\views\\data-siswa.blade.php', 'utf8');
const scripts = content.match(/<script[\s\S]*?<\/script>/gi);

scripts.forEach((script, i) => {
    console.log(`\n\n=== Script ${i} ===`);
    console.log(script);
});
