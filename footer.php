<script>
    function supprimer(pid) {
        window.location.replace('modify.php?type=delete&pid=' + pid)
    }
    function modifier(pid) {
        window.location.replace('index.php?pid=' + pid)
    }
</script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
<script src="semantic-ui/semantic.min.js"></script>
</body>
</html>