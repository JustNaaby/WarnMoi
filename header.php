<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AWarn2</title>
    <link rel="stylesheet" type="text/css" href="semantic-ui/semantic.min.css">
</head>
<body>
<h1>AWarn2</h1>
<div class="ui top attached tabular menu">
    <a class="item <?php if (empty($_GET)): echo 'active'; endif ?>" onclick="liste_des_warns()">
        Liste des warns
    </a>
    <a class="item <?php if (isset($_GET['add'])): echo 'active'; endif ?>" onclick="ajouter()">
        Ajouter un warn
    </a>
    <a class="item <?php if (!isset($_GET['pid'])): echo 'disabled'; else: echo 'active'; endif ?>">
        Modifier un warn
    </a>
    <a class="item <?php if (!isset($_GET['steamid'])): echo 'disabled'; else: echo 'active'; endif ?>">
        Recherche <?php if (isset($_GET['steamid'])): echo '(' . $_GET['steamid'] . ')'; endif ?>
    </a>
    <div class="right menu">
        <div class="item">
            <div class="ui transparent icon input" data-children-count="1">
                <input type="text" id="steamid" value="<?php if (isset($_GET['steamid'])): echo $_GET['steamid']; endif ?>" placeholder="SteamID...">
                <i class="search link icon" onclick="recherche()"></i>
            </div>
        </div>
    </div>
</div>
<script>
    function liste_des_warns() {
        window.location.replace('index.php')
    }
    function recherche() {
        var steamid = document.getElementById("steamid").value;
        window.location.replace('index.php?steamid=' + steamid)
    }
    function ajouter() {
        window.location.replace('index.php?add=<?php if (isset($_GET['steamid'])): echo $_GET['steamid']; endif ?>')
    }
</script>