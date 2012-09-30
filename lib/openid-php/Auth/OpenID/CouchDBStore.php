<?php

/**
 * This file supplies a Memcached store backend for OpenID servers and
 * consumers.
 *
 * PHP versions 4 and 5
 *
 * @package OpenID
 * @author Stafford Horne <shorne@gmail.com>
 * @copyright 2012 STAFFORD
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache
 */

/**
 * Require base class for creating a new interface.
 */
require_once 'Auth/OpenID.php';
require_once 'Auth/OpenID/Interface.php';
require_once 'Auth/OpenID/HMAC.php';
require_once 'Auth/OpenID/Nonce.php';

/**
 * CouchDB Store implementation
 *
 * @package OpenID
 */
class Auth_OpenID_CouchDBStore {

    function Auth_OpenID_CouchDBStore () {
      $this->association = new CouchDB(DB_HOST, DB_PORT);
      $this->association->database = DB_NAME;
      $this->association->table = 'association';

      $this->nonce = new CouchDB(DB_HOST, DB_PORT);
      $this->nonce->database = DB_NAME;
      $this->nonce->table = 'nonce';
    }
   
    /**
     * This method puts an Association object into storage,
     * retrievable by server URL and handle.
     *
     * @param string $server_url The URL of the identity server that
     * this association is with. Because of the way the server portion
     * of the library uses this interface, don't assume there are any
     * limitations on the character set of the input string. In
     * particular, expect to see unescaped non-url-safe characters in
     * the server_url field.
     *
     * @param Association $association The Association to store.
     */
    function storeAssociation($server_url, $association)
    {
      $assoc = array(
        '_id' => $server_url,
        'handle' => $association->handle,
        'secret' => base64_encode($association->secret),
        'issued' => $association->issued,
        'lifetime' => $association->lifetime,
        'assoc_type' => $association->assoc_type,
      
        
      );

      $this->association->save($server_url, $assoc);
    }

    /*
     * Remove expired nonces from the store.
     *
     * Discards any nonce from storage that is old enough that its
     * timestamp would not pass useNonce().
     *
     * This method is not called in the normal operation of the
     * library.  It provides a way for store admins to keep their
     * storage from filling up with expired data.
     *
     * @return the number of nonces expired
     */
    function cleanupNonces()
    {
        trigger_error("Auth_OpenID_OpenIDStore::cleanupNonces ".
                      "not implemented", E_USER_ERROR);
    }

    /*
     * Remove expired associations from the store.
     *
     * This method is not called in the normal operation of the
     * library.  It provides a way for store admins to keep their
     * storage from filling up with expired data.
     *
     * @return the number of associations expired.
     */
    function cleanupAssociations()
    {
        trigger_error("Auth_OpenID_OpenIDStore::cleanupAssociations ".
                      "not implemented", E_USER_ERROR);
    }

    /*
     * Shortcut for cleanupNonces(), cleanupAssociations().
     *
     * This method is not called in the normal operation of the
     * library.  It provides a way for store admins to keep their
     * storage from filling up with expired data.
     */
    function cleanup()
    {
        return array($this->cleanupNonces(),
                     $this->cleanupAssociations());
    }

    /**
     * Report whether this storage supports cleanup
     */
    function supportsCleanup()
    {
        return false;
    }

    /**
     * This method returns an Association object from storage that
     * matches the server URL and, if specified, handle. It returns
     * null if no such association is found or if the matching
     * association is expired.
     *
     * If no handle is specified, the store may return any association
     * which matches the server URL. If multiple associations are
     * valid, the recommended return value for this method is the one
     * most recently issued.
     *
     * This method is allowed (and encouraged) to garbage collect
     * expired associations when found. This method must not return
     * expired associations.
     *
     * @param string $server_url The URL of the identity server to get
     * the association for. Because of the way the server portion of
     * the library uses this interface, don't assume there are any
     * limitations on the character set of the input string.  In
     * particular, expect to see unescaped non-url-safe characters in
     * the server_url field.
     *
     * @param mixed $handle This optional parameter is the handle of
     * the specific association to get. If no specific handle is
     * provided, any valid association matching the server URL is
     * returned.
     *
     * @return Association The Association for the given identity
     * server.
     */
    function getAssociation($server_url, $handle = null)
    {
      $assoc = $this->association->select($server_url);
      if(isset($assoc)) { 
        return new Auth_OpenID_Association($assoc['handle'],
                                            base64_decode($assoc['secret']),
                                            $assoc['issued'],
                                            $assoc['lifetime'],
                                            $assoc['assoc_type']);
      } else {
        return null;
      }
    }

    /**
     * This method removes the matching association if it's found, and
     * returns whether the association was removed or not.
     *
     * @param string $server_url The URL of the identity server the
     * association to remove belongs to. Because of the way the server
     * portion of the library uses this interface, don't assume there
     * are any limitations on the character set of the input
     * string. In particular, expect to see unescaped non-url-safe
     * characters in the server_url field.
     *
     * @param string $handle This is the handle of the association to
     * remove. If there isn't an association found that matches both
     * the given URL and handle, then there was no matching handle
     * found.
     *
     * @return mixed Returns whether or not the given association existed.
     */
    function removeAssociation($server_url, $handle)
    {
        trigger_error("Auth_OpenID_OpenIDStore::removeAssociation ".
                      "not implemented", E_USER_ERROR);
    }

    /**
     * Called when using a nonce.
     *
     * This method should return C{True} if the nonce has not been
     * used before, and store it for a while to make sure nobody
     * tries to use the same value again.  If the nonce has already
     * been used, return C{False}.
     *
     * Change: In earlier versions, round-trip nonces were used and a
     * nonce was only valid if it had been previously stored with
     * storeNonce.  Version 2.0 uses one-way nonces, requiring a
     * different implementation here that does not depend on a
     * storeNonce call.  (storeNonce is no longer part of the
     * interface.
     *
     * @param string $nonce The nonce to use.
     *
     * @return bool Whether or not the nonce was valid.
     */
    function useNonce($server_url, $timestamp, $salt)
    {
        global $Auth_OpenID_SKEW;

        if ( abs($timestamp - time()) > $Auth_OpenID_SKEW ) {
            return false;
        }

        $nonce = array( 
          'server_url' => $server_url,
          'timestamp' => $timestamp,
          'salt' => $salt,
        );

        return $this->nonce->save($server_url, $nonce);
    }

    /**
     * Removes all entries from the store; implementation is optional.
     */
    function reset()
    {
    }

}
