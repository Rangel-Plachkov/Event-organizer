<?php
$routesRegistrator = \Router\Registrator::getInstance();

//INDEX ROUTES
$routesRegistrator->map("GET","[index@index]","/","indexPage");

//PAGE ROUTES
$routesRegistrator->map("GET","[page@signUp]","sign-up","signUpPage");

//USER ROUTES
$routesRegistrator->map("POST","[user@createAcc]","create-acc","createAcc");
