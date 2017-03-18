const http = require('http');
const fs = require('fs');

const PORT_OR_SOCKET = '/var/run/docker/comm.sock';
const isSocket = isNaN(Number(PORT_OR_SOCKET));

const server = http.createServer((req, res) => {
  res.end('Hello! From Node.js');
});

server.on('error', (err) => {
  if (isSocket && err.code == 'EADDRINUSE') {
    console.log('[server] cleaning up old socket');
    fs.unlinkSync(PORT_OR_SOCKET);
    server.listen(PORT_OR_SOCKET);
  } else {
    throw err;
  }
});

server.listen(PORT_OR_SOCKET, (err) => {
  if (isSocket) {
    console.log('[server] allowing non-root users access to socket');
    fs.chmodSync(PORT_OR_SOCKET, '777');
  }
});
