<!-- END CHANGEABLE CONTENT -->
</main>

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