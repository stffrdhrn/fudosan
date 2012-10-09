<form method="post" action="index.php?url=login/try_auth">
 <legend>Login with OpenID</legend>
 <label>OpenID</label>
 <input name="openid_identifier" type="text" value=""/>

 <div class="form-actions">
 <button type="submit" class="btn btn-primary">Submit</button>
 </div>

<a href="index.php?url=login/try_auth&openid_identifier=https://www.google.com/accounts/o8/id" class="btn">Use Google</a>
<a href="index.php?url=login/try_auth&openid_identifier=https://me.yahoo.com" class="btn">Use Yahoo!</a>
</form>
