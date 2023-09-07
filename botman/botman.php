<?php

require_once 'vendor/autoload.php';

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\SymfonyCache;
use BotMan\BotMan\Drivers\DriverManager;

//Agregados pruebas de enrique
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\Middleware\DialogFlow\V2\DialogFlow;
use App\http\Controllers\BotManController;
use Mpociot\BotMan\Messages\Message;
/////////////////////////

require_once('conversaciones/OnboardingConversation.php');
require_once('conversaciones/PedirNombreConversation.php');

$config = [];

DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

$adapter = new FilesystemAdapter();

$botman = BotManFactory::create($config, new SymfonyCache($adapter));

$storage = $botman->userStorage();

// $dialogflow = BotMan\Middleware\DialogFlow\V2\DialogFlow::create('en');
// $botman->middleware->received($dialogflow);
// $botman->hears('smalltalk.(.*)', function ($bot) {
//     $extras = $bot->getMessage()->getExtras();
//     $bot->reply($extras['apiReply']);
// })->middleware($dialogflow);


$botman->hears('Hello', function($bot) {
    
    $bot->startConversation(new OnboardingConversation);
    
});

$botman->hears('Hola', function($bot) {
    
    $bot->startConversation(new OnboardingConversation);
    
});

/* $botman->hears('(.*)Quiero(.*)imagenes(.*)', function ($bot) {
    $bot->ask('Cuales imagenes?', function ($answer, $bot) {
        $text = $answer->getText();
        $image = Image::url('' . $text); // 
        $message = OutgoingMessage::create()->withAttachment($image);
        $bot->say($message);
    });
});
 */

//Pruebas enrique

//Respuesta simple
$botman->hears('()Que(.*)onda(.*)|(.*)que tal(.*)todo(.*)', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("¿Que tal todo?");
    
});

$botman->hears('(.*)(¿Cuánto cuesta|Precio de|Valor de)(.*)', function ($bot, $product) {
    $bot->typesAndWaits(1);
    $bot->reply("El precio de $product depende del modelo y la talla. ¿Puedo ayudarte a encontrar algo en particular?");
});

$botman->hears('(Novedades|Lo más reciente|Últimos productos)(.*)', function ($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("¡Tenemos lo Último lanzado de una nueva colección de camisetas de verano! Visita la página para ver los nuevos estilos.");
});

$botman->hears('(Recomiéndame|Que me sugieres|Dame una idea de)(.*)', function ($bot, $context) {
    $bot->typesAndWaits(1);
    $bot->reply("¡Claro! Tenemos una gran variedad de camisetas en diferentes estilos y colores. ¿Tienes alguna preferencia en mente?");
});

$botman->hears('(.*)Me gustaria(.*)asesoría(.*)|me(.*)asesoria(.*)|Me(.*)gustaria(.*)|Necesito(.*)|asesoría(.*)', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("Claro!!, ¿Cómo puedo ayudarte?");
    
});

$botman->hears('(.*)((.*)Tienen en stock(.*)|Tienen(.*)disponibilidad de)(.*)', function ($bot, $product) {
    $bot->typesAndWaits(1);
    $bot->reply("Sí, tenemos $product en stock. ¿En qué más puedo ayudarte?");
});


$botman->hears('Como(.*)ves(.*)|futuro(.*)', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("Ser parte la empresa líder en Nicaragua en el área de comercialización de productos textiles de marcas premium de alcance internacional"); 
});


$botman->hears('(.*)ofertas(.*)descuentos(.*)|descuento(.*)', function($bot) {
        $bot->typesAndWaits(1);
        $bot->reply("En este momento, tenemos un 10% de descuento en todas las camisetas de manga corta. ¿Te gustaría ver nuestra selección?");
    });



    
/* Buenos deseos  */
$botman->hears('Que(.*)tengas(.*)|buen día', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("Muchas gracias, el Dios de las IAS me cuida");
    
});
/* Fin de buenos deseos */ 

/* Problemas */
$botman->hears('Tuve(.*)Problemas(.*)con(.*)camisas', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("Odio los problemas, pero no me les escondo");  
});
/* Fin problemas */

/* Broma */
$botman->hears('(.*)tiempo(.*)libre(.*)|(.*)tiempos(.*)libres', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("Soy bailarina de las chicas toña"); 
});
/* Fin broma  */

/* Frase Celebre */
$botman->hears('(.*)Dime(.*)frase(.*)|celebre(.*)', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("Mi éxito se lo debo al hecho de que nunca tuve un reloj en mi taller,
    Thomas Edison"); 
});
/* Fin frase Celebre  */

/* Datos curiosos */
$botman->hears('(.*)dato(.*)curioso(.*)', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("Tus ojos hacen más ejercicio que tus piernas"); 
});
/* Fin datos curiosos  */

/*fundacion de Beachy: */
$botman->hears('(.*)año(.*)inicio(.*)', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("Beachy inició en 2022"); 
});
/* Fin de fundacion de Beachy */ 



/* Tallas */
$botman->hears('(.*)tallas(.*)tienes(.*)|talla(.*)', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("Te invito a ver la página, porque no recuerdo"); 
});
/* Fin tallas */

$botman->fallback(function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply('Lo siento, lo que me dices está fuera de mi alcance por el momento.');
    //$bot->reply('Tengo una lista de preguntas frecuentes por si te ayudan a aclarar tus dudas.');
});





$botman->hears('(.*)recomiendas(.*)usar(.*)|(.*)recomienda(.*)|(.*)Quiero(.*)que(.*)recomiendes(.*)camisas(.*)', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("Una camiseta Vineyard Vines te quedaría genial"); 
}); 


//Fin preguntas simples














//Inicio Respuestas con Bases de datos
$botman->hears('(.*)Quiero(.*)camisas(.*)disponibles(.*)|(.*)camisa(.*)disponible(.*)|(.*)camiseta(.*)venta(.*)', function($bot) {
    require_once "../config/server.php" ;
    $bot->typesAndWaits(1);
    $bot->reply("Claro!!, Aqui tienes las camisas disponibles");

    $connect = new PDO("mysql:host=".SERVER."; dbname=".DB, USER, PASS);

    $query = "
        SELECT * FROM tblproducto
        INNER JOIN catcolores ON tblproducto.colorProducto = catcolores.idColor
        INNER JOIN catcategorias ON tblproducto.categoriaProducto = catcategorias.idCategoria
        WHERE catcategorias.idCategoria != '1'
    ";
    
    $statement = $connect->prepare($query);
    
    $statement->execute();

    $statement = $statement->fetchAll(PDO::FETCH_ASSOC);

    //$statement = json_encode($statement);
    //echo "<script>console.log(".$statement.")</script>";

    foreach ($statement as $row) {
        $attachment = new Image('../img/imgProductos/'.$row["codigoEstilo"].'/back.jpeg');

        $message = OutgoingMessage::create(''.$row["descripcionProducto"].'')
                    ->withAttachment($attachment);
        $bot->typesAndWaits(1);
        $bot->reply($message);
        //$bot->reply($row['idProducto'].""); //recordar ponerle eso al final
        }
    
});



$botman->hears('(.*)estilo(.*)favorito(.*)', function($bot) {
    $bot->typesAndWaits(1);
    $bot->reply("¿Prefieres camisetas de manga corta o manga larga?");
    require_once "../config/server.php" ;

    $connect = new PDO("mysql:host=".SERVER."; dbname=".DB, USER, PASS);

    $query = "
        SELECT * FROM tblproducto
        INNER JOIN catcategorias ON tblproducto.categoriaProducto = catcategorias.idCategoria
        INNER JOIN cattela ON tblproducto.telaProducto = cattela.idTela
        WHERE catcategorias.idCategoria = '3'
    ";
    
    $statement = $connect->prepare($query);
    
    $statement->execute();

    $statement = $statement->fetchAll(PDO::FETCH_ASSOC);

    //$statement = json_encode($statement);
    //echo "<script>console.log(".$statement.")</script>";

    foreach ($statement as $row) {
        $attachment = new Image('../img/imgProductos/'.$row["codigoEstilo"].'/back.jpeg');

        $message = OutgoingMessage::create(''.$row["descripcionProducto"].'')
                    ->withAttachment($attachment);
        $bot->typesAndWaits(1);
        $bot->reply($message);
        //$bot->reply($row['idProducto'].""); //recordar ponerle eso al final
        }

    //OTRA MANERA DE HACERLO
    // for ($i=0; $i < count($statement); $i++) { 
    //     $bot->reply($statement[$i]['idProducto']."");
    // } 
    
});



$botman->hears('(.*)ceo(.*)', function ($bot) {
    // Create attachment
    $bot->typesAndWaits(1);
    $bot->reply("Nuestro CEO se llama: Enrique Muñoz");
    $attachment = new Image('../img/CEO.jpeg');

    // Build message object
    $message = OutgoingMessage::create('Aquí una foto')
                ->withAttachment($attachment);

    // Reply message object
    $bot->reply($message);
});

$botman->hears('(.*)equipo(.*)|desarrollo(.*)', function ($bot) {
    // Create attachment
    $bot->typesAndWaits(1);
    $bot->reply("Nuestro equipo desarrollador se llama Codenaut");
    $attachment = new Image('../img/Code.jpeg');

    // Build message object
    $message = OutgoingMessage::create('Aquí una foto')
                ->withAttachment($attachment);

    // Reply message object
    $bot->reply($message);
});

$botman->hears('(.*)Barcelona(.*)|(.*)Real Madrid(.*)', function ($bot) {
    // Create attachment
    $bot->typesAndWaits(1);
    $bot->reply("Yo le voy a la Xavineta");
    $attachment = new Image('https://pbs.twimg.com/media/FcFg81JWIAM8R2w.jpg');

    // Build message object
    $message = OutgoingMessage::create('Aquí una foto')
                ->withAttachment($attachment);

    // Reply message object
    $bot->reply($message);
});
//Para desarrollar luego
/*
$botman->hears('Musica(.*)', function ($bot) {
    // Create attachment
    $attachment = new video('https://www.youtube.com/watch?v=scWSTDsj3IM', [
        'custom_payload' => true,
    ]);
    // Build message object
    $message = OutgoingMessage::create('Esta es mi canción favorita :D')
                ->withAttachment($attachment);

    // Reply message object
    $bot->reply($message);
    //$bot->reply("Esta es mi canción fav https://www.youtube.com/watch?v=scWSTDsj3IM");
});
*/


$botman->hears('imagen', function ($bot) {
    // Create attachment
    $attachment = new Image('https://botman.io/img/logo.png');

    // Build message object
    $message = OutgoingMessage::create('This is my text')
                ->withAttachment($attachment);

    // Reply message object
    $bot->reply($message);
});

$botman->hears('(.*)Logo(.*)|(.*)logo(.*)tienda(.*)||(.*)Tienda(.*)|(.*)logo(.*)empresa(.*)', function ($bot) {
    // Create attachment
    $attachment = new Image('../img/LOGO SIN FONDO.png');

    // Build message object
    $message = OutgoingMessage::create('Este es el logo de nuestra tienda amigo! :D')
                ->withAttachment($attachment);

    // Reply message object
    $bot->reply($message);
});


    $botman->hears('(.*)Tienes(.*)camisas(.*)|(.*)ver(.*)productos(.*)color {color}', function($bot, $color) {
        require_once "../config/server.php" ;

        $connect = new PDO("mysql:host=".SERVER."; dbname=".DB, USER, PASS);

        $query = "
            SELECT * FROM tblproducto
            INNER JOIN catcolores ON tblproducto.colorProducto = catcolores.idColor
            INNER JOIN catcategorias ON tblproducto.categoriaProducto = catcategorias.idCategoria
            INNER JOIN cattela ON tblproducto.telaProducto = cattela.idTela
            WHERE catcolores.nombreColor LIKE '%".$color."%'
        ";



    $statement = $connect->prepare($query);

    $statement->execute();

    if($statement->rowCount() < 1)
    {
        $bot->reply("No hay productos disponibles que coincidan con lo que buscas por el momento.");
    }
    else
    {

        $statement = $statement->fetchAll(PDO::FETCH_ASSOC);

        //$statement = json_encode($statement);
        //echo "<script>console.log(".$statement.")</script>";
        $bot->reply("Te muestro productos que sean de color ".$color."");

        foreach ($statement as $row) {
            $attachment = new Image('../img/imgProductos/'.$row["codigoEstilo"].'/back.jpeg');

            $message = OutgoingMessage::create(''.$row["descripcionProducto"].'')
                        ->withAttachment($attachment);
            $bot->reply($message);
            //$bot->reply($row['idProducto']."");//recordar ponerle eso al final
            }
    }
    //OTRA MANERA DE HACERLO
    // for ($i=0; $i < count($statement); $i++) { 
    //     $bot->reply($statement[$i]['idProducto']."");
    // } 

});



$botman->hears('(.*)disponibles(.*)(.*)', function($bot) {
    require_once "../config/server.php" ;

    $connect = new PDO("mysql:host=".SERVER."; dbname=".DB, USER, PASS);

    $query = "
        SELECT * FROM tblproducto
        INNER JOIN catcolores ON tblproducto.colorProducto = catcolores.idColor
        INNER JOIN catcategorias ON tblproducto.categoriaProducto = catcategorias.idCategoria
        INNER JOIN cattela ON tblproducto.telaProducto = cattela.idTela
    ";
    
    $statement = $connect->prepare($query);
    
    $statement->execute();

    $statement = $statement->fetchAll(PDO::FETCH_ASSOC);

    //$statement = json_encode($statement);
    //echo "<script>console.log(".$statement.")</script>";

    foreach ($statement as $row) {
        $attachment = new Image('../img/imgProductos/'.$row["codigoEstilo"].'/back.jpeg');

        $message = OutgoingMessage::create(''.$row["descripcionProducto"].'')
                    ->withAttachment($attachment);
        $bot->reply($message);
        //$bot->reply($row['idProducto']."");//recordar ponerle eso al final
        }

    //OTRA MANERA DE HACERLO
    // for ($i=0; $i < count($statement); $i++) { 
    //     $bot->reply($statement[$i]['idProducto']."");
    // } 
    
});


/* fin preguntas con base de datos */    


  
   








//Probando guardar la info del usuario
// $botman->hears('mi nombre es {nombre}', function ($bot, $nombre) {

//     $bot->userStorage()->save([
//         'nombre' => $nombre
//     ]);

//     // Reply message object
//     $bot->reply('Hola '.$nombre);
// });

$botman->hears('(.*)mi id(.*)', function ($bot) {
    // $nombreUser = $bot->userStorage()->get('nombre');
    // $bot->reply('Tu nombre es '.$nombreUser);
    // $bot->userStorage()->save([
    //     'last' => 'ti'
    // ]);

    $nombreUser = $bot->userStorage()->get('nombre');
    $bot->reply('Tu nombre es '.$nombreUser);
    

    $bot->reply('tu id es: '.$bot->getUser()->getId());

});


//Este parametro es enviado desde public/bottom.php con el comentario <!-- Parametro bot inicial -->
$botman->hears('storeInfo123 {nombre}', function ($bot, $nombre) {
    //$bot->reply('Tu nombre es dddd');
    //session_start();
    $nombreUser = $bot->userStorage()->get('nombre');
    if($nombreUser == 'undefined' || $nombreUser == '')//Si no tiene un nombre asignado en el bot se buscara el nombre
    {
        if ($nombre == 'undefined' || $nombre == '')//Si el usuario no tiene iniciada sesion se le pedira el nombre mediante el bot
        {
            $bot->startConversation(new PedirNombreConversation);
            //$bot->startConversation(new OnboardingConversation);
        }
        else//Si tiene sesion iniciada se guardara el nombre de la bd en el bot
        {
            $bot->userStorage()->save([
                'nombre' => $nombre
            ]);
        }
    }

});


$botman->listen();