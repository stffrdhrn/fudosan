<div class="container">
<div class="span11">
<h2>Sign in to <em>Sabaai</em> with</h2>
<a href="<?php echo AppHelper::route('login','try_auth')?>&openid_identifier=https://www.google.com/accounts/o8/id" class="btn"><img src='img/google-icon.png'> Google</a>
<a href="<?php echo AppHelper::route('login','try_auth')?>&openid_identifier=https://me.yahoo.com" class="btn"><img src='img/yahoo-icon.png' /> Yahoo!</a>

<form method="post" action="<?php echo AppHelper::route('login','try_auth')?>">
 <legend>or OpenID use</legend>
 <label>OpenID</label>
 <input name="openid_identifier" type="text" value=""/>

<div class="form-actions">
 <button type="submit" class="btn">Sign In</button>
</div>

</form>
</div>
</div>
