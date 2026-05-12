<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home redirect to participant login
$routes->get('/', 'SubmitWork::login');

// ===== ADMIN LOGIN — no filter =====
$routes->get('admin/login', 'Admin::loginPage');
$routes->post('admin/login', 'Admin::doLogin');
$routes->get('admin/logout', 'Admin::doLogout');

// ===== ADMIN ROUTES — protected by adminauth filter on the group =====
$routes->group('admin', ['filter' => 'adminauth'], function ($routes) {
    $routes->get('/', 'Admin::dashboard');
    $routes->get('dashboard', 'Admin::dashboard');

    // Participants
    $routes->get('participants', 'Admin::participants');
    $routes->post('participants/import', 'Admin::importParticipants');
    $routes->post('participants/add', 'Admin::addParticipant');
    $routes->post('participants/edit/(:num)', 'Admin::editParticipant/$1');
    $routes->get('participants/delete/(:num)', 'Admin::deleteParticipant/$1');
    $routes->get('participants/export', 'Admin::exportParticipants');

    // Assignments
    $routes->get('assignments', 'Admin::assignments');
    $routes->post('assignments/save', 'Admin::saveAssignment');
    $routes->post('assignments/edit/(:num)', 'Admin::editAssignment/$1');
    $routes->get('assignments/delete/(:num)', 'Admin::deleteAssignment/$1');

    // Submissions overview
    $routes->get('submissions', 'Admin::submissions');
    $routes->get('submissions/files/(:num)', 'Admin::submissionFiles/$1');
    $routes->get('submissions/delete-file/(:num)', 'Admin::deleteFile/$1');

    // WordCloud admin
    $routes->get('wordcloud', 'Admin::wordcloud');
    $routes->get('wordcloud/clear', 'Admin::clearWordcloud');
});

// ===== PARTICIPANT ROUTES =====
$routes->get('login', 'SubmitWork::login');
$routes->post('login', 'SubmitWork::doLogin');
$routes->get('logout', 'SubmitWork::logout');
$routes->get('my-work', 'SubmitWork::myWork');
$routes->get('submit/(:num)', 'SubmitWork::submit/$1');
$routes->post('submit/(:num)', 'SubmitWork::doSubmit/$1');
$routes->get('submit/(:num)/delete-file/(:num)', 'SubmitWork::deleteFile/$1/$2');

// File serving (for uploaded images)
$routes->get('files/(:segment)', 'Files::serve/$1');

// ===== WORDCLOUD (public) =====
$routes->get('wordcloud', 'WordCloud::index');
$routes->post('wordcloud', 'WordCloud::store');
$routes->get('wordcloud/display', 'WordCloud::display');
$routes->get('wordcloud/data', 'WordCloud::data');
