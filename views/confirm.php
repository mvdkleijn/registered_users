<?php

$snippet = Snippet::findByName('ru-confirmation');

if (false !== $snippet) {
    eval('?' . '>' . $snippet->content_html);
    return true;
} else {
    ?>

<p><label for="email">Email :</label> <input id="email" type="text" name="email" value="" /></p>
<p><label for="rand_key">Authorisation Code :</label> <input id="rand_key" type="text" name="rand_key" value="" /></p>
<p><label for="submit"></label><input id="submit_btn" class="btn submit" type="submit" accesskey="s" value="Activate your account" /></p>

<?php } ?>