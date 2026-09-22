<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Class Model (Abstract Base Model)
 *
 * Every entity model (User, Photo, Comment) extends this class to gain
 * a shared PDO connection. All child models are expected to write their
 * own raw, parameterized SQL queries -- no ORM is used per the project
 * constraints.
 *
 * @package core
 */
abstract class Model
{
    /** @var PDO Shared database connection handle */
    protected PDO $db;

    /**
     * Model constructor.
     * Pulls the singleton PDO connection so every model shares one
     * underlying connection resource.
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
}
