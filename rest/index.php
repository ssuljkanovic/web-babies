<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS, PATCH');

require '../vendor/autoload.php';

Flight::register('db', 'PDO', array('mysql:host=remotemysql.com;dbname=XNAdCYmAPp', 'XNAdCYmAPp', 'E9OiG6uBTR'));

Flight::route('GET /v1/baby_count', function () {
    $baby_count = Flight::db()->query('SELECT gender, COUNT(ID) as count FROM Babies group by gender', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($baby_count);
});

Flight::route('GET /v1/babies', function () {
    $babies = Flight::db()->query('SELECT * FROM Babies', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($babies);
});

Flight::route('POST /v1/babies', function () {
    $request = Flight::request()->data->getData();
    unset($request['psword']);
    $insert = "INSERT INTO Babies (mothersName, gender, dateBirth, timeBirth, weight, height) VALUES(:fmother, :fgender, :fdate, :ftime, :fweight, :fheight)";  // fname, lname, user_email and phone are names of input fields
    $stmt = Flight::db()->prepare($insert);
    $stmt->execute($request);
});

Flight::route('DELETE /v1/babies/@id', function ($id) {
    $delete = "DELETE FROM Babies WHERE ID = :id";
    $stmt = Flight::db()->prepare($delete);
    $stmt->execute([":id" => $id]);
});

Flight::start();
