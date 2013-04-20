<?php

$snippet = Snippet::findByName('ru-reset');

if (false !== $snippet) {
    eval('?' . '>' . $snippet->content_html);
    return true;
} else {
    ?>

<p><label for="email">Email :</label> <input id="email" type="text" name="email" value="" /></p>
<p><label for="submit"></label> <input id="submit_btn" class="btn submit" type="submit" accesskey="s" value="Reset your password" /></p>

<?php } ?>