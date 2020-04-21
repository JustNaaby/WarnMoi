<?php
include 'config.php';
include 'steamid.php';
$steamid32 = filter_input(INPUT_GET, 'steamid', FILTER_SANITIZE_STRING);
$dsn = 'mysql:host=' . $mysql['hote'] . ';dbname=' . $mysql['dbname'] . ';charset=utf8';
$dbh = new PDO($dsn, $mysql['username'], $mysql['mdp'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
if (!empty($steamid32)) {
    $unique_id = toCommunityID($steamid32);
    $requete = "SELECT * FROM awarn_warnings WHERE unique_id=:unique_id ORDER BY date DESC;";
    $sth = $dbh->prepare($requete);
    $sth->bindParam(':unique_id', $unique_id, PDO::PARAM_STR);
    $sth->execute();
} else {
    $query = "SELECT * FROM awarn_warnings ORDER BY date DESC LIMIT 30;";
    $sth = $dbh->query($query);
}
$data = $sth->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
if (isset($_GET['pid']))://Onglet modifier
    $requete = <<<PDO
            SELECT * FROM awarn_warnings WHERE pid=:pid
PDO;
    $sth = $dbh->prepare($requete);
    $sth->bindParam(':pid', $_GET['pid'], PDO::PARAM_INT);
    $sth->execute();
    $data = $sth->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="ui bottom attached segment">
    <h4 class="ui dividing header">Modifier le warn n°<?= $data["pid"] ?> de <?= toSteamID($data['unique_id']) ?></h4>
    <form class="ui form" action="modify.php" type="GET">
        <div class="field">
            <label>Raison du warn</label>
            <div class="field">
                <input type="text" name="reason" value="<?= $data["reason"] ?>" placeholder="Raison">
            </div>
        </div>
        <input value="modify" name="type" type="hidden">
        <input value="<?= $data['pid'] ?>" name="pid" type="hidden">
        <input class="ui button" type="submit" tabindex="0"/>
    </form>

<?php
elseif (isset($_GET['add']))://Onglet ajouter
    ?>
    <div class="ui bottom attached segment">
    <form class="ui form" method="GET" action="modify.php">
    <h4 class="ui dividing header">Ajouter un warn</h4>
    <div class="three fields">
    <div class="field">
        <label for="unique_id">SteamID</label>
        <div class="field">
            <input type="text" name="unique_id" id="unique_id" placeholder="SteamID">
        </div>
    </div>
    <div class="field">
        <label for="reason">Raison</label>
        <div class="field">
            <input type="text" name="reason" id="reason" placeholder="Raison">
        </div>
    </div>
    <div class="field">
        <label for="admin">Votre Nom</label>
        <div class="field">
            <input type="text" name="admin" id="admin" placeholder="Nom RP">
        </div>
    </div>
    </div>
    <input type="hidden" name="type" value="add">
    <input class="ui button" type="submit">
<?php
else:
    ?>
    <div class="ui bottom attached segment">
        <table class="ui celled table">
            <thead>
            <tr>
                <th>SteamID</th>
                <th>Raison</th>
                <th>Date</th>
                <th>Warn par</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $ligne): ?>
                <tr>
                    <td data-label="SteamID"><?= toSteamID($ligne["unique_id"]) ?></td>
                    <td data-label="Raison"><?= $ligne["reason"] ?></td>
                    <td data-label="Date"><?= strftime("%A %-d %B - %kh%M", strtotime($ligne["date"])) ?></td>
                    <td data-label="Warn par"><?= $ligne["admin"] ?></td>
                    <td>
                        <div class="ui small basic icon buttons">
                            <button class="ui button" onclick="supprimer(<?= $ligne["pid"] ?>)"><i
                                        class="delete icon red"></i></button>
                            <button class="ui button" onclick="modifier(<?= $ligne["pid"] ?>)"><i
                                        class="edit icon blue"></i></button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php include 'footer.php';?>
