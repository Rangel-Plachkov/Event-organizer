<?php

$routesRegistrator = \Router\Registrator::getInstance();

//INDEX ROUTES
$routesRegistrator->map("GET", "[index@index]", "/", "indexPage");

//PAGE ROUTES
$routesRegistrator->map("GET", "[page@signUp]", "sign-up", "signUpPage");
$routesRegistrator->map("GET", "[page@signIn]", "sign-in", "signInPage");
$routesRegistrator->map("GET", "[page@edit]", "edit", "editPage");
$routesRegistrator->map("GET", "[page@createEvent]", "create-event", "create_event-page");
$routesRegistrator->map("GET", "[page@listEvents]", "event-list", "eventList");
$routesRegistrator->map("POST", "[page@eventDashboard]", "event-dashboard", "event-dashboard");
$routesRegistrator->map("GET", "[page@viewProfile]", "viewProfile", "viewProfile");
$routesRegistrator->map("GET", "[page@search]", "search", "search");
$routesRegistrator->map("GET", "[page@myProfile]", "myProfile", "myProfile");
$routesRegistrator->map("GET", "[page@about]", "about", "about");

//USER ROUTES
$routesRegistrator->map("POST", "[user@createAcc]", "create-acc", "createAcc");
$routesRegistrator->map("POST", "[user@login]", "login", "login");
$routesRegistrator->map("GET", "[user@logout]", "logout", "logout");
$routesRegistrator->map("POST", "[user@editAcc]", "edit-acc", "editAcc");
$routesRegistrator->map("GET", "[user@deleteAcc]", "delete-acc", "deleteAcc");
$routesRegistrator->map("POST", "[user@searchUser]", "user-search", "user-search");
$routesRegistrator->map("POST", "[user@follow]", "follow", "follow");
$routesRegistrator->map("POST", "[user@unfollow]", "unfollow", "unfollow");



//EVENT ROUTES
$routesRegistrator->map("POST", "[event@createEvent]", "create-event-op", "createEvent");
$routesRegistrator->map("POST", "[event@deleteEvent]", "delete-event", "delete-event-operation");
$routesRegistrator->map("POST", "[event@addOrganization]", "add-organization-op", "add-ogranization-operation");
$routesRegistrator->map("POST", "[event@joinOrganization]", "join-event-btn", "join-ogranization-operation");
//GIFT ROUTES
$routesRegistrator->map("POST", "[event@addGift]", "add-gift", "add-gift-op");
$routesRegistrator->map("POST", "[event@voteForGift]", "vote-gift", "vote-gift-op");
$routesRegistrator->map("POST", "[event@endPoll]", "end-poll", "end-poll-op");
$routesRegistrator->map("POST", "[event@createPoll]", "create-poll", "create-poll-op");
//COMMENT ROUTES
$routesRegistrator->map("POST", "[event@createComment]", "create-comment-op", "createComment");


//ERROR PAGES ROUTES
$routesRegistrator->map("GET", "[error@error404]", "error/404", "pageNotFoundError");
$routesRegistrator->map("GET", "[error@error500]", "error/500", "serverError");
$routesRegistrator->map("GET", "[error@unhandledError]", "error/503", "unhandledError");
$routesRegistrator->map("GET", "[error@error404user]", "error/404user", "userNotFoundError");