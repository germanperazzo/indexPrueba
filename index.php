<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();


//usuario

$app->post('/usuario', function (Request $request, Response $response)
{
    $data = $request->getParsedBody();
    $nombre_usuario = $data['nombre_usuario'];
    $clave = $data['clave']; 
    $es_admin = $data['es_admin']; 

        $conn = new mysqli("localhost", "root", "", "seminariophp");
        if ($conn->connect_errno) {
        $response->getBody()->write("Fallo al conectar a MySQL: " . $conn->connect_error);
        return $response;
        }     

        $stmt = $conn->prepare("INSERT INTO usuario (nombre_usuario, clave, es_admin) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $nombre_usuario, $clave, $es_admin);
        $stmt->execute();

    
    

    $payload = $stmt ? ['message' => 'Usuario creado exitosamente'] : ['error' => 'Error al crear usuario'];

  
    $statusCode = $stmt ? 201 : 500;

    $response->getBody()->write(json_encode($payload));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
});



$app->put('/usuario/{id}',function (Request $request, Response $response,$args)
{
    $id = $args['id'];

    $data = $request->getParsedBody();
    
    
    $nombre_usuario = $data['nombre_usuario'] ;
    $clave = $data['clave'] ;
    //$token = $data['token'] ;
    //$vencimiento_token = $data['vencimiento_token'] ;
    $es_admin = $data['es_admin'];
    

    $conn = new mysqli("localhost", "root", "", "seminariophp");
    if ($conn->connect_errno) {
    $response->getBody()->write("Fallo al conectar a MySQL: " . $conn->connect_error);
    return $response;
    }     

    $stmt = $conn->prepare("UPDATE usuario SET nombre_usuario = ?, clave = ?, es_admin = ? WHERE id = ?");
    $stmt->bind_param('ssii', $nombre_usuario, $clave , $es_admin , $id );
    $stmt->execute();
    

    $payload = $stmt ? ['message' => 'Usuario actualizado exitosamente'] : ['error' => 'Error al actualizar usuario'];

    $statusCode = $stmt ? 200 : 500;

    $response->getBody()->write(json_encode($payload));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
} );

$app->delete('/usuario/{id}',  function (Request $request, Response $response, array $args)
{
    $id = $args['id'];
    
    $conn = new mysqli("localhost", "root", "", "seminariophp");
    if ($conn->connect_errno) {
        $response->getBody()->write("Fallo al conectar a MySQL: " . $conn->connect_error);
    return $response;
    }

    $stmt = $conn->prepare("DELETE FROM usuario WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    

    $payload = $stmt ? ['message' => 'Usuario eliminado correctamente'] : ['error' => 'Error al eliminar usuario'];

    $statusCode = $stmt ? 200 : 500;

    $response->getBody()->write(json_encode($payload));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
});

$app->get('/usuario/{id}', function (Request $request, Response $response, $args)
{
    $id = $args['id'];
    
    $conn = new mysqli("localhost", "root", "", "seminariophp");
    if ($conn->connect_errno) {
        $response->getBody()->write("Fallo al conectar a MySQL: " . $conn->connect_error);
    return $response;
    }

        $stmt = $conn->prepare("SELECT * FROM usuario WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

    if ($user) {
        $response->getBody()->write(json_encode($user));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['error' => 'Usuario no encontrado']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
});

//juego

$app->get('/juegos/{id}', function (Request $request, Response $response, $args)
{
    // falta traer las calificacionnes    
    $id = $args['id'];

    $conn = new mysqli("localhost", "root", "", "seminariophp");
    if ($conn->connect_errno) {
        $response->getBody()->write("Fallo al conectar a MySQL: " . $conn->connect_error);
    return $response;
    }
    
    $stmt = $conn->prepare("SELECT * FROM juego WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $game = $result->fetch_assoc();


    if ($game) {
        $response->getBody()->write(json_encode($game));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['error' => 'Usuario no encontrado']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
});

$app->post('/juegos', function (Request $request, Response $response)
{
    $data = $request->getParsedBody();
    $nombre = $data['nombre'];
    $descripcion = $data['descripcion'];
    $imagen = $data['imagen'];
    $clasificacion_edad= $data['clasificacion_edad'];

    $conn = new mysqli("localhost", "root", "", "seminariophp");
    if ($conn->connect_errno) {
        $response->getBody()->write("Fallo al conectar a MySQL: " . $conn->connect_error);
    return $response;
    }

    $stmt = $conn->prepare("INSERT INTO juego (nombre, descripcion, imagen, clasificacion_edad) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $nombre, $descripcion, $imagen, $clasificacion_edad);
    $stmt->execute();

    $payload = $stmt ? ['message' => 'juego creado exitosamente'] : ['error' => 'Error al crear usuario'];

    $statusCode = $stmt ? 201 : 500;

    $response->getBody()->write(json_encode($payload));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
});

$app->put('/juegos/{id}', function (Request $request, Response $response, $args)
{
    $id = $args['id'];

    $data = $request->getParsedBody();
    $nombre = $data['nombre'];
    $descripcion = $data['descripcion'];
    $imagen = $data['imagen'];
    $clasificacion_edad= $data['clasificacion_edad'];
    

    $conn = new mysqli("localhost", "root", "", "seminariophp");
    if ($conn->connect_errno) {
    $response->getBody()->write("Fallo al conectar a MySQL: " . $conn->connect_error);
    return $response;
    }     

    $stmt = $conn->prepare("UPDATE juego SET nombre = ?, descripcion = ?, imagen = ?, clasificacion_edad = ? WHERE id = ?");
    $stmt->bind_param('ssssi', $nombre, $descripcion, $imagen, $clasificacion_edad, $id );
    $stmt->execute();
    

    $payload = $stmt ? ['message' => 'juego actualizado exitosamente'] : ['error' => 'Error al actualizar juego'];

    $statusCode = $stmt ? 200 : 500;

    $response->getBody()->write(json_encode($payload));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
} );

$app->delete('/juegos/{id}', function (Request $request, Response $response, array $args)
{
    $id = $args['id'];
    
    $conn = new mysqli("localhost", "root", "", "seminariophp");
    if ($conn->connect_errno) {
        $response->getBody()->write("Fallo al conectar a MySQL: " . $conn->connect_error);
    return $response;
    }

    $stmt = $conn->prepare("DELETE FROM juego WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    

    $payload = $stmt ? ['message' => 'juego eliminado correctamente'] : ['error' => 'Error al eliminar juego'];

    $statusCode = $stmt ? 200 : 500;

    $response->getBody()->write(json_encode($payload));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
});

/*
$app->post('/login', [UserController::class, 'login']);
$app->post('/register', [UserController::class, 'register']);
$app->get('/juegos', [GameController::class, 'listGames']); lista

$app->post('/calificacion', [RatingController::class, 'createRating']);
$app->put('/calificacion/{id}', [RatingController::class, 'updateRating']);
$app->delete('/calificacion/{id}', [RatingController::class, 'deleteRating']);



*/
//$app->addRoutingMiddleware();

//require __DIR__ . '/../src/routes/routes.php';


$app->run();