<!-- END CHANGEABLE CONTENT -->
</main>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

<footer class="siteFooter">
    This is the footer.
</footer>

</body>
</html>

<?php
// Close the PDO connection.
MySqlPDO::closeConnection();

// Send the buffer to the browser.
ob_end_flush();
?>