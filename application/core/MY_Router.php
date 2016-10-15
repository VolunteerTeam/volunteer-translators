<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * My Router Class
 *
 * Parses URIs and determines routing
 *
 * @package        CodeIgniter+DB_Router
 * @subpackage    Libraries
 * @author        ExpressionEngine Dev Team and Big_Shark
 * @category    Libraries
 * @link        http://codeigniter.com/user_guide/general/routing.html
 */
class MY_Router extends CI_Router {

    /**
     * Constructor
     */
    function __construct()
    {

        parent::__construct();

    }
    /**
     *  Parse Routes
     *
     * This function matches any routes that may exist in
     * the config/routes.php file or db against the URI to
     * determine if the class/method need to be remapped.
     *
     * @access    private
     * @return    void
     */
    function _parse_routes()
    {
        // Do we even have any custom routing to deal with?
        // There is a default scaffolding trigger, so we'll look just for 1

        if (count($this->routes) == 1)
        {
            $this->_set_request($this->uri->segments);
            return;
        }



        // Turn the segment array into a URI string
        $uri = implode('/', $this->uri->segments);


        // Is there a literal match?  If so we're done
        if (isset($this->routes[$uri]))
        {
            $this->_set_request(explode('/', $this->routes[$uri]));
            return;
        }

        //DB Router
        //-------------------------------------------------
        if($this->config->item('db_router') && ($db = $this->custom_connect_db())!=null)
        {


            $array_uri = $this->uri->segments;

            /*if(count($array_uri)>1)
            {

                $method = array_shift($array_uri);
                $parameters = implode("/",$array_uri);

                $sth1 = $db->prepare("SELECT url,redirect FROM seo WHERE module_name = ? AND  subject_id =? LIMIT 1");
                $sth1->setFetchMode(PDO::FETCH_OBJ);
                $sth1->execute(array($method,$parameters));

                if($r = $sth1->fetch())
                {
                    $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
                    header("Location: ".$host.$r->url, TRUE, $r->redirect);
                }
            }*/


            $sth2 = $db->prepare("SELECT `module_name`,`action`,`subject_id`,'redirect' FROM seo WHERE url = ?  LIMIT 1");
            $sth2->setFetchMode(PDO::FETCH_OBJ);
            $sth2->execute(array($uri));



            if ($r = $sth2->fetch())
            {
                $action = !empty($r->action) ? $r->action.'/' :'';
                $this->_set_request(explode('/',"front/".$r->module_name."/".$action.$r->subject_id));
                return;
            }
            else
            {
                // Loop through the route array looking for wild-cards
                foreach ($this->routes as $key => $val)
                {
                    // Convert wild-cards to RegEx
                    $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

                    // Does the RegEx match?
                    if (preg_match('#^'.$key.'$#', $uri))
                    {
                        // Do we have a back-reference?
                        if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
                        {
                            $val = preg_replace('#^'.$key.'$#', $val, $uri);
                        }

                        $this->_set_request(explode('/', $val));
                        return;
                    }
                }



            }/**/
        }
        else
        {
            // Loop through the route array looking for wild-cards
            foreach ($this->routes as $key => $val)
            {
                // Convert wild-cards to RegEx
                $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

                // Does the RegEx match?
                if (preg_match('#^'.$key.'$#', $uri))
                {
                    // Do we have a back-reference?
                    if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
                    {
                        $val = preg_replace('#^'.$key.'$#', $val, $uri);
                    }

                    $this->_set_request(explode('/', $val));
                    return;
                }
            }
        }
        //-------------------------------------------------

        // If we got this far it means we didn't encounter a
        // matching route so we'll set the site default route
        $this->_set_request($this->uri->segments);
    }


    function custom_connect_db()
    {
        if ( ! file_exists($file_path = APPPATH.'config/database.php'))
        {
            show_error('The configuration file database.php does not exist.');
        }
        include($file_path);

        $cfg = $db[$active_group];
        $dsn = $cfg['dbdriver'].':dbname='.$cfg['database'].';host='.$cfg['hostname'];
        $dbh = null;

        try {
            $dbh = new PDO($dsn, $cfg['username'], $cfg['password']);
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }

        return $dbh;

    }



}
// END Router Class

/* End of file MY_Router.php */
/* Location: ./system/application/libraries/MY_Router.php */ 