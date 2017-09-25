<?php
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<script>ShowWaitingMessage('Trwa przekierowanie do zgłszenia nr <?php echo $_REQUEST[search_zgl_nr]; ?>...');</script>
<?php ob_flush(); flush(); ?>
<script>self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=<?php echo $_REQUEST[search_zgl_nr]; ?>';</script>