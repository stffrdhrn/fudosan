<?php
function getScheme() {
    $scheme = 'http';
    if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
        $scheme .= 's';
    }
    return $scheme;
}

function getReturnTo() {
    return sprintf("%s://%s:%s%s/index.php?url=login/finish_auth",
                   getScheme(), $_SERVER['SERVER_NAME'],
                   $_SERVER['SERVER_PORT'],
                   dirname($_SERVER['PHP_SELF']));
}

function getTrustRoot() {
    return sprintf("%s://%s:%s%s/",
                   getScheme(), $_SERVER['SERVER_NAME'],
                   $_SERVER['SERVER_PORT'],
                   dirname($_SERVER['PHP_SELF']));
}


class LoginController extends Controller {
  function displayError($message) {
    $this->set('error', $message);
    $this->redirect('start');
    exit(0);
  }

  function getOpenIDURL() {
    if (empty($_REQUEST['openid_identifier'])) {
        $this->displayError("Expected an OpenID URL.");
    }

    return $_REQUEST['openid_identifier'];
  }

  function &getStore() {
    $r = new Auth_OpenID_CouchDBStore();
    return $r;
  }

  function &getConsumer() {
    /**
     * Create a consumer object using the store object created
     * earlier.
     */
    $store = $this->getStore();
    $r = new Auth_OpenID_Consumer($store);
    return $r;
  }

  # ACTIONS
  function start() {
    $this->set('title', 'Login');
  }

  function try_auth() {
    $openid = $this->getOpenIDURL();
    $consumer = $this->getConsumer();

    // Begin the OpenID authentication process.
    $auth_request = $consumer->begin($openid);

    // No auth request means we can't begin OpenID.
    if (!$auth_request) {
        $this->displayError("Authentication error; not a valid OpenID.");
    }

    $sreg_request = Auth_OpenID_SRegRequest::build(
                                     // Required
                                     array('nickname'),
                                     // Optional
                                     array('fullname', 'email'));

    if ($sreg_request) {
        $auth_request->addExtension($sreg_request);
    }

    // Redirect the user to the OpenID server for authentication.
    // Store the token for this authentication so we can verify the
    // response.

    // For OpenID 1, send a redirect.  For OpenID 2, use a Javascript
    // form to send a POST request to the server.
    if ($auth_request->shouldSendRedirect()) {
        $redirect_url = $auth_request->redirectURL(getTrustRoot(),
                                                   getReturnTo());

        // If the redirect URL can't be built, display an error
        // message.
        if (Auth_OpenID::isFailure($redirect_url)) {
            $this->displayError("Could not redirect to server: " . $redirect_url->message);
        } else {
            // Send redirect.
            header("Location: ".$redirect_url);
        }
    } else {
        // Generate form markup and render it.
        $form_id = 'openid_message';
        $form_html = $auth_request->htmlMarkup(getTrustRoot(), getReturnTo(),
                                               false, array('id' => $form_id));

        // Display an error if the form markup couldn't be generated;
        // otherwise, render the HTML.
        if (Auth_OpenID::isFailure($form_html)) {
            $this->displayError("Could not redirect to server: " . $form_html->message);
        } else {
            $this->set('form', $form_html);
            $this->set('title', 'Redirecting');
            $this->redirect('redirect');
        }
    }
  }

  function finish_auth() {
    $consumer = $this->getConsumer();

    // Complete the authentication process using the server's
    // response.
    $return_to = getReturnTo();
    $response = $consumer->complete($return_to);

    // Check the response status.
    if ($response->status == Auth_OpenID_CANCEL) {
        // This means the authentication was cancelled.
        $msg = 'Verification cancelled.';
    } else if ($response->status == Auth_OpenID_FAILURE) {
        // Authentication failed; display the error message.
        $msg = "OpenID authentication failed: " . $response->message;
    } else if ($response->status == Auth_OpenID_SUCCESS) {
        // This means the authentication succeeded; extract the
        // identity URL and Simple Registration data (if it was
        // returned).
        $openid = $response->getDisplayIdentifier();
        $esc_identity = htmlentities($openid);

        $success = sprintf('You have successfully verified ' .
                           '<a href="%s">%s</a> as your identity.',
                           $esc_identity, $esc_identity);

        if ($response->endpoint->canonicalID) {
            $escaped_canonicalID = htmlentities($response->endpoint->canonicalID);
            $success .= '  (XRI CanonicalID: '.$escaped_canonicalID.') ';
        }

        $sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);

        $sreg = $sreg_resp->contents();

        if (@$sreg['email']) {
            $success .= "  You also returned '".htmlentities($sreg['email']).
                "' as your email.";
        }

        if (@$sreg['nickname']) {
            $success .= "  Your nickname is '".htmlentities($sreg['nickname']).
                "'.";
        }

        if (@$sreg['fullname']) {
            $success .= "  Your fullname is '".htmlentities($sreg['fullname']).
                "'.";
        }
    }

    if(isset($msg)) {
      $this->displayError($msg);
    }
    if($success) {
      $this->set('success', $success);
    }

    $login = $this->set_login($openid);
    if (!$login) {
      # No login in the DB yet, we need to create a profile
      $this->edit($openid);
      $this->redirect('edit');
    } else {
      $this->set('title', "Welcome ".$login['name']);
      $this->set('model', $login);
    }
  }

  function edit($id = null) {
    $login = $this->Login->select($id);    
 
    if (!$login) {
      $this->set('title', 'Create a new profile'); 
      $this->set('model', array('_id' => $id)); 
    } else {
      $this->set('title', 'Editing '.$login['name'].' profile'); 
      $this->set('model', $login); 
    }

  }

  function save() {
    $id = $_POST['_id'];
    $result = $this->Login->save($id, $_POST);

    if(isset($result['error'])) {
      $this->set('error', $result['error'] ); 
    } else {
      $this->set('success', $_POST['name'] . ' saved');
    }

    # Update the login properties 
    if ($id == $_SESSION['login']) { 
      $this->set_login($id);
    }
    $this->edit($id);
    $this->redirect('edit');

    return $id;

  }

  function listall() {
    $this->set('title', 'Lising all users');
    $this->set('model', $this->Login->select_all());
  }

  function logout() {
    $this->set_login(null);
    $this->set('title', "Logout Complete");
    $this->set('success', "Logout Complete");
    $this->redirect('start');
  }
}
