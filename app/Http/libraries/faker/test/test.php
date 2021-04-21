<?php
//require __DIR__ .'/../vendor/autoload.php';

require __DIR__ .'/../src/autoload.php';
require __DIR__ .'/../../funciones_comunes.php';

date_default_timezone_set("America/Argentina/Buenos_Aires");
// 'es_ES'

$sql  = "";
$path = "http://localhost/SICOLOGIA/dinamicos/noticias/";


$faker = Faker\Factory::create();

for ($i=0; $i < 10; $i++) 
{
	$titulo = $faker->unique()->sentence(15);
	$url = $faker->url;
	
	$nombre = $faker->name;
	//echo $faker->text(500);	
	$titulo = $titulo;
	$uamigable = urlsAmigables($titulo);
	$fingreso = sistemaGetDate();
	$fpublicacion = $faker->date($format = 'Y-m-d', $max = 'now');
	//echo $faker->date($format = 'Y-m-d', $max = 'now') . " " . $faker->time($format = 'H:i:s', $max = 'now');
	$imagen = $path . $faker->creditCardNumber . ".jpg";	
	$creador = $faker->randomElement(["admin", "web", "visualchile"]);
	$url = $url;
	$tipo = $faker->randomElement(["noticias", "articulos"]);
	$descripcion = $faker->paragraph(200);
	$posicion = $i + 1;
	$estado = 1;
	
	$sql .= "INSERT INTO noticias (tipo, titulo, uamigable, descripcion, imagen, fpublicacion, posicion, estado, fingreso, creador) ";
	$sql .= "VALUES ('$tipo', '$titulo', '$uamigable', '', '$imagen', '$fpublicacion', $posicion, $estado, '$fingreso', '$creador');<br>";	
}

echo $sql;
