const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const app = express();
const server = http.createServer(app);
const io = new Server(server);

io.on('connection', (socket) => {
  console.log('A user connected');

  // Listen for 'chat message' event
  socket.on('chat message', (data) => {
    // Broadcast the message to all connected clients
    io.emit('chat message', { 
      user: data.user, 
      message: data.message 
    });
  });

  socket.on('disconnect', () => {
    console.log('User disconnected');
  });
});

server.listen(3000, () => {
  console.log('Listening on *:3000');
});
