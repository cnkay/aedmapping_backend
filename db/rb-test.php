<?php

include 'rb-db-config.php';

function cleanAllTables() {
    $tables = R::getCol(' show tables ');
    foreach ($tables as $table) {
        if($table=='admin') {
            continue;
        }
        R::wipe($table);
    }
}

R::nuke();


$comment = R::dispense('comment');
$comment->name = 'commentName';
$comment->comment = 'commentComment';
$comment->createdAt = 'createdAt';

$reports = R::dispense('report');
$reports->type = 'reportType';
$reports->comment = 'reportComment';
$reports->mail = 'reportMail';


$defibrillator = R::dispense('defibrillator');
$defibrillator->name = 'defibName';
$defibrillator->description = 'defibDesc';
$defibrillator->address = 'defibAddress';
$defibrillator->city = 'defibCity';
$defibrillator->country = 'deficCountry';
$defibrillator->latitude = '39.828788';
$defibrillator->longitude = '32.100251';
$defibrillator->ownCommentsList[] = $comment;
$defibrillator->ownReportsList[] = $reports;
R::store($defibrillator);


$ekab = R::dispense('admin');
$ekab->mail = 'mail5';
$ekab->password = hash('sha256', 'password5');
$ekab->role = 'ekab';
R::store($ekab);

$munic = R::dispense('admin');
$munic->mail = 'mail10';
$munic->password = hash('sha256', 'password10');
$munic->role = 'munic';
R::store($munic);


$user = R::dispense('user');
$user->name='firstname lastname';
$user->mail = 'mail';
$user->password = hash('sha256','password');
R::store($user);

R::exec('SET FOREIGN_KEY_CHECKS = 0; ');
cleanAllTables();
R::exec('SET FOREIGN_KEY_CHECKS = 1; ');