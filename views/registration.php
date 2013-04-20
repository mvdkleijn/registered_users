<?php

$snippet = Snippet::findByName('ru-registration');

if (false !== $snippet) {
    eval('?' . '>' . $snippet->content_html);
    return true;
} else {
    ?>

    <p><label for="name">Name</label>
        <input class="text-input validate['required']" id="name" maxlength="100" name="name" size="20" type="text" value="" /></p>
    <p><label class="optional" for="email">E-mail</label>
        <input class="text-input validate['required','email']" id="email" maxlength="40" name="email" size="20" type="text" value="" /></p>
    <p><label for="username">Username</label>
        <input class="text-input validate['required']" id="username" maxlength="40" name="username" size="20" type="text" value="" /></p>
    <p><label for="password">Password</label>
        <input class="text-input validate['required']" id="password" maxlength="40" name="password" size="20" type="password" value="" /></p>
    <p><label for="confirm_pass">Confirm Password</label>
        <input class="text-input validate['required','confirm[password]']" id="confirm_pass" maxlength="40" name="confirm_pass" size="20" type="password" value="" /></p>
    <p><label for="signup"> </label><input id="submit_btn" class="btn submit" type="submit" accesskey="s" value="Register" /></p>

<?php } ?>