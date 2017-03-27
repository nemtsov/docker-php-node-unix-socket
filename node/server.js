const http = require('http');
const fs = require('fs');

const PORT_OR_SOCKET = process.env.PORT || '/var/run/docker/comm.sock';
const isSocket = isNaN(Number(PORT_OR_SOCKET));

function getData(req) {
  return new Promise((resolve, reject) => {
    const buffers = [];
    req.on('data', data => buffers.push(data));
    req.on('end', () => resolve(Buffer.concat(buffers)));
  });
}

const server = http.createServer((req, res) => {

  console.log('NODE-METH:', req.method);
  console.log('NODE-HEADERS:', req.headers);

  getData(req).then((data) => {
    console.log('DATA: ', data.toString());
  });

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
