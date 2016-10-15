<?php

class MY_Session extends CI_Session
{

    public $cart_table_name = 'cart';

    // --------------------------------------------------------------------

    /**
     * Update an existing session
     *
     * @access	public
     * @return	void
     */
    function sess_update()
    {
        // We only update the session every five minutes by default
        if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
        {
            return;
        }

        // Save the old session id so we know which record to
        // update in the database if we need it
        $old_sessid = $this->userdata['session_id'];
        $new_sessid = '';
        while (strlen($new_sessid) < 32)
        {
            $new_sessid .= mt_rand(0, mt_getrandmax());
        }

        // To make the session ID even more secure we'll combine it with the user's IP
        $new_sessid .= $this->CI->input->ip_address();

        // Turn it into a hash
        $new_sessid = md5(uniqid($new_sessid, TRUE));

        // Update the session data in the session data array
        $this->userdata['session_id'] = $new_sessid;
        $this->userdata['last_activity'] = $this->now;

        // _set_cookie() will handle this for us if we aren't using database sessions
        // by pushing all userdata to the cookie.
        $cookie_data = NULL;

        // Update the session ID and last_activity field in the DB if needed
        if ($this->sess_use_database === TRUE)
        {
            // set cookie explicitly to only have our session data
            $cookie_data = array();
            foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
            {
                $cookie_data[$val] = $this->userdata[$val];
            }

            $this->CI->db->query($this->CI->db->update_string($this->sess_table_name, array('last_activity' => $this->now, 'session_id' => $new_sessid), array('session_id' => $old_sessid)));

            /*SPECIAL FOR TASHA - Содержимое корзины привязывается к идентификатору сессии. При обновлении глобального id - меняем его в таблице cart*/
            $this->CI->db->query($this->CI->db->update_string($this->cart_table_name, array('session_id' => $new_sessid), array('session_id' => $old_sessid)));
        }

        // Write the cookie
        $this->_set_cookie($cookie_data);
    }

    // --------------------------------------------------------------------

    /**
     * Add or change data in the "userdata" array
     *
     * @access	public
     * @param	mixed
     * @param	string
     * @return	void
     */
    function set_userdata($newdata = array(), $newval = '')
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $this->userdata[$key] = $val;
            }
        }




        $this->sess_write();
    }

}
