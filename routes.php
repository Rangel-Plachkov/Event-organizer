<?php
$routesRegistrator = \Router\Registrator::getInstance();

//INDEX ROUTES
$routesRegistrator->map("GET","[index@index]","/","indexPage");

//PAGE ROUTES
$routesRegistrator->map("GET","[page@signUp]","sign-up","signUpPage");
$routesRegistrator->map("GET","[page@signIn]","sign-in","signInPage");

//USER ROUTES
$routesRegistrator->map("POST","[user@createAcc]","create-acc","createAcc");
$routesRegistrator->map("POST","[user@login]","login","login");
