<?php

/**
 * Created by syscom.
 * User: syscom
 * Date: 17/06/2019
 * Time: 15:50
 */

namespace DBMaker\ODBC;

use Exception;

class DBMakerODBCPdo
{
    protected $connection;
    protected $options ;

    /**
     * Get the column listing for a given table.
     *
     * @param  string  $dsn
     * @param  string  $username
     * @param  string  $passwd
     * @param  array   $options
     * @return array
     */
    public function __construct($dsn,$username,$passwd,$options=[]) {
    	 $connect = odbc_connect($dsn,$username,$passwd);
        $this->setConnection($connect);
        $this->options = $options;
    }

    public function exec($query) {
        return $this->prepare($query)->execute();
    }

    /**
     *
     * @param  string  $statement
     * @param  array  $driver_options
     * @return Dbmaker\Odbc\DBMakerODBCPdoStatement	
     */
    public function prepare($statement,$driver_options = null) {
    	  return new DBMakerODBCPdoStatement($this->getConnection(),$statement,$this->options);
    }

    /**
     * @return mixed
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * @param mixed $connection
     * @return void
     */
    public function setConnection($connection) {
		$this->connection = $connection;
	}
    
    /**
     * @return void
     */
    public function commit() {
        return odbc_commit($this->getConnection());
     }
     
    public function rollBack() {
        $rollback = odbc_rollback($this->getConnection());
        odbc_autocommit($this->getConnection(),true);
        return $rollback;
    }
    
    /**
     * @return void
     */
    public function beginTransaction() {
        odbc_autocommit($this->getConnection(),false);
    }
}