const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
const bodyParser = require('body-parser');

// Inicializa Express
const app = express();
const server = http.createServer(app);

// Permitir CORS en el servidor Express
app.use(cors({
    origin: '*', // Permitir todas las solicitudes (para pruebas)
}));

const io = socketIo(server, {
    cors: {
        origin: "*",  // Esto permite todas las solicitudes. Puedes limitarlo a un dominio específico si lo prefieres.
        methods: ["GET", "POST"]
    }
});



// Utilizar body-parser para analizar los datos de las solicitudes POST
app.use(express.json());

// Puerto donde el servidor escuchará las conexiones
const PORT = 3000;

// Ruta básica para verificar si el servidor está funcionando
app.get('/', (req, res) => {
    res.send('Servidor WebSocket funcionando');
});


// Ruta para recibir notificaciones
app.post('/notificar', (req, res) => {
    const data = req.body;

    if (!data || !data.mensaje) {
        res.status(400).send('Datos inválidos');
        return;
    }

    // Emitir a todos los clientes conectados
    io.emit('notificacion', data);
    console.log('Notificación enviada:', data);

    res.status(200).send('OKk');
});

// Evento de conexión con WebSocket
io.on('connection', (socket) => {
    console.log('Nuevo cliente conectado');

// Ejemplo de emitir una notificación al conectarse
    //socket.emit('notificacion', { mensaje: 'Bienvenido, ¡tienes nuevas notificaciones!' });

    // Desconexión del cliente
    socket.on('disconnect', () => {
        console.log('Cliente desconectado');
    });
});

// Iniciar el servidor
server.listen(PORT, () => {
    console.log(`Servidor WebSocket corriendo en http://localhost:${PORT}`);
});
