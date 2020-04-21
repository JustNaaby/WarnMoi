<?php
include 'steamid.php';
include 'config.php';
$dsn = 'mysql:host=' . $mysql['hote'] . ';dbname=' . $mysql['dbname'] . ';charset=utf8';
$dbh = new PDO($dsn, $mysql['username'], $mysql['mdp'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pid = filter_input(INPUT_GET, 'pid', FILTER_VALIDATE_INT);
$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
$unique_id = filter_input(INPUT_GET, 'unique_id', FILTER_SANITIZE_STRING);
$reason = filter_input(INPUT_GET, 'reason', FILTER_SANITIZE_STRING);
$admin = filter_input(INPUT_GET, 'admin', FILTER_SANITIZE_STRING);
////////////////////////////////////////////////////////////////////////
//Suppression d'un warn
if (!empty($pid) and $type == 'delete') {
////////////////////////////////
    //1ère requête qui recupère le steamid64 du joueur
    $requete = <<<PDO
SELECT unique_id FROM awarn_warnings WHERE pid=:pid
PDO;
    $sth = $dbh->prepare($requete);
    $sth->bindParam(':pid', $pid, PDO::PARAM_INT);
    $sth->execute();
    $data = $sth->fetch(PDO::FETCH_ASSOC);
    $unique_id = $data["unique_id"];
////////////////////////////////
    //2eme requête qui recupère le nombre de warn actif en fonction du steamid64 (unique_id)
    $requete = <<<PDO
SELECT warnings FROM awarn_playerdata WHERE unique_id=:unique_id
PDO;
    $sth = $dbh->prepare($requete);
    $sth->bindParam(':unique_id', $unique_id, PDO::PARAM_INT);
    $sth->execute();
    $data = $sth->fetch(PDO::FETCH_ASSOC);
    $warnings = $data['warnings'];
////////////////////////////////
    //3ème requête qui supprimer le warn et retire 1 warn actif
    if (!$warnings == 0) {
        $warnings -= 1;
        $requete = <<<PDO
DELETE FROM awarn_warnings WHERE pid=:pid;
UPDATE awarn_playerdata SET warnings=:warnings WHERE unique_id=:unique_id;
PDO;

        $sth = $dbh->prepare($requete);
        $sth->bindParam(':pid', $pid, PDO::PARAM_INT);
        $sth->bindParam(':warnings', $warnings, PDO::PARAM_INT);
        $sth->bindParam(':unique_id', $unique_id, PDO::PARAM_STR);
        $sth->execute();
    }
////////////////////////////////
}
////////////////////////////////////////////////////////////////////////
//Modifier un warn
if (!empty($pid) and $type == 'modify') {
////////////////////////////////
    //Modifie le warn
    $requete = <<<PDO
UPDATE awarn_warnings SET reason=:reason WHERE pid=:pid;
PDO;
    $sth = $dbh->prepare($requete);
    $sth->bindParam(':pid', $pid, PDO::PARAM_STR);
    $sth->bindParam(':reason', $reason, PDO::PARAM_STR);
    $sth->execute();
////////////////////////////////
}
////////////////////////////////////////////////////////////////////////
//Ajouter un warn
if (!empty($reason) and $type == 'add' and !empty($unique_id) and !empty($admin)) {
////////////////////////////////
    //1ère requête qui recupère le nombre de warn actif en fonction du steamid64 (unique_id)
    $unique_id = toCommunityID($unique_id);
    $requete = <<<PDO
SELECT warnings FROM awarn_playerdata WHERE unique_id=:unique_id
PDO;
    $sth = $dbh->prepare($requete);
    $sth->bindParam(':unique_id', $unique_id, PDO::PARAM_INT);
    $sth->execute();
    $data = $sth->fetch(PDO::FETCH_ASSOC);
    $warnings = $data['warnings'];
////////////////////////////////
    //Ajoute 1 warn actif
    if (empty($warnings)) {//Si jamais eu de warn
        $lastwarn = time();
        $warnings = 1;
        $requete = <<<PDO
INSERT INTO awarn_playerdata (unique_id, warnings, lastwarn) VALUES (:unique_id, :warnings, :lastwarn);
PDO;
        $sth = $dbh->prepare($requete);
        $sth->bindParam(':warnings', $warnings, PDO::PARAM_INT);
        $sth->bindParam(':unique_id', $unique_id, PDO::PARAM_STR);
        $sth->bindParam(':lastwarn', $lastwarn, PDO::PARAM_INT);
        $sth->execute();
    } else {
        $warnings += 1;
        $requete = <<<PDO
UPDATE awarn_playerdata SET warnings=:warnings WHERE unique_id=:unique_id;
PDO;

        $sth = $dbh->prepare($requete);
        $sth->bindParam(':warnings', $warnings, PDO::PARAM_INT);
        $sth->bindParam(':unique_id', $unique_id, PDO::PARAM_STR);
        $sth->execute();
    }
////////////////////////////////
    //3ème requête ajoute le warn
    $date = date('D M j G:i:s Y', time());
    $requete = <<<PDO
INSERT INTO awarn_warnings (unique_id, admin, reason, date, server) VALUES (:unique_id, :admin, :reason, :date, "Server 1");
PDO;
    $sth = $dbh->prepare($requete);
    $sth->bindParam(':unique_id', $unique_id, PDO::PARAM_INT);
    $sth->bindParam(':reason', $reason, PDO::PARAM_STR);
    $sth->bindParam(':admin', $admin, PDO::PARAM_STR);
    $sth->bindParam(':date', $date, PDO::PARAM_STR);
    //$sth->bindParam(':date', $date, PDO::PARAM_STR);
    $sth->execute();
////////////////////////////////
}
header("Location: index.php");
?>
