<?php
class Employee{

    // Connection instance
    private $connection;

    // table name
    private $table_name = "employee";

    // table columns
    public $system_id;
    public $name;
    public $uid;
    public $cid;
    public $date;
    public $coffee_cup_allowance;
    public $tea_cup_allowance;
    public $cCups;
    public $tCups; 
    public $d3;
    public $d4;
    public $mid;
    public $machine;
    public $time;
    public $cCost;
    public $tCost;
    public $total;

    public function __construct($connection){
        $this->connection = $connection;
    }

    //C
    public function create(){
    }
    //R
    public function read(){
        $query = "SELECT system_id, name, id_code, commercial_id, date, coffee_cup_allowance, tea_cup_allowance, coffee_cups, tea_cups,drink3, drink4, machine_id, machine_name, time FROM " . $this->table_name;

        $stmt = $this->connection->prepare($query);

        $stmt->execute();

        return $stmt;
    }
    //U
    public function update(){}
    //D
    public function delete(){}

    //getMenuDataApiQuery
    //fetch employees data with machine id and from-to date parameters
    public function getMenuData($mid, $from, $to){
      $query = "SELECT
      A.*,
      IFNULL(B.`cCups`, 0) AS `cCups`,
      IFNULL(B.`tCups`,0) AS `tCups`,
      IFNULL(B.`d3`,0) AS `d3`,
      IFNULL(B.`d4`,0) AS `d4`,
      IFNULL(B.`cCost`,0) AS `cCost`,
      IFNULL(B.`tCost`, 0) AS `tCost`,
      IFNULL(B.`total`,0) AS `total`
    FROM
      (SELECT 
       `Employee Name` as `name`,
      `ID Code` as `uid`,
      LPAD(`Commercial ID`, 7, 0) AS `cid`
      FROM
      `MASTER_DATA`
      GROUP BY
      `Employee Name`,
      `ID Code`,
      `Commercial ID`
      ORDER BY
      `ID Code`
    ) A
    RIGHT OUTER JOIN
      (
      SELECT `ID Code NO.` as `uid`,
      SUM(`Coffee Cups`) AS `cCups`,
      SUM(`Tea Cups`) AS `tCups`,
      SUM(`Drink3`) AS `d3`,
      SUM(`Drink4`) AS `d4`,
      SUM(IF(`Coffee Cups` > `Coffee Cup Allowance`,`Coffee Cups` - `Coffee Cup Allowance`,0)*(SELECT `price` FROM `cost` WHERE `drink` = 'Coffee')) AS `cCost`,
      SUM(IF(`Tea Cups` > `Tea Cup Allowance`,`Tea Cups` - `Tea Cup Allowance`, 0) *(SELECT `price` FROM `cost` WHERE `drink` = 'Tea')) AS `tCost`,
      SUM((IF(`Coffee Cups` > `Coffee Cup Allowance`,`Coffee Cups` - `Coffee Cup Allowance`, 0) *( SELECT `price` FROM `cost` WHERE `drink` = 'Coffee')) +(IF(`Tea Cups` > `Tea Cup Allowance`,`Tea Cups` - `Tea Cup Allowance`,0) *(SELECT `price` FROM `cost` WHERE `drink` = 'Tea'))) AS `total`
      FROM
      `EMPLOYEE`
      WHERE
      `Machine Id` LIKE '%$mid%' AND (`Date` BETWEEN '$from' AND '$to')
      GROUP BY
      `ID Code NO.`
      ORDER BY
      `ID Code NO.`
    ) B ON B.`uid` = A.uid " ;

      $stmt = $this->connection->prepare($query);

      $stmt->execute();

      return $stmt;
    }

    //fetch indivisual employee's data with emlpoyee id and from-to date parameters
    public function getEmpData($eid, $from, $to){
      $query = "SELECT
      DATE_FORMAT(`Date`, '%d-%m-%Y') AS `date`,
      SUM(`Coffee Cups`) AS `cCups`,
      SUM(`Tea Cups`) AS `tCups`,
      SUM(
          IF(
              `Coffee Cups` > `Coffee Cup Allowance`,
              `Coffee Cups` - `Coffee Cup Allowance`,
              0
          ) *(
          SELECT
              `price`
          FROM
              `cost`
          WHERE
              `drink` = 'Coffee'
      )
      ) AS `cCost`,
      SUM(
          IF(
              `Tea Cups` > `Tea Cup Allowance`,
              `Tea Cups` - `Tea Cup Allowance`,
              0
          ) *(
          SELECT
              `price`
          FROM
              `cost`
          WHERE
              `drink` = 'Tea'
      )
      ) AS `tCost`,
      SUM(
          (
              IF(
                  `Coffee Cups` > `Coffee Cup Allowance`,
                  `Coffee Cups` - `Coffee Cup Allowance`,
                  0
              ) *(
              SELECT
                  `price`
              FROM
                  `cost`
              WHERE
                  `drink` = 'Coffee'
          )
          ) +(
              IF(
                  `Tea Cups` > `Tea Cup Allowance`,
                  `Tea Cups` - `Tea Cup Allowance`,
                  0
              ) *(
              SELECT
                  `price`
              FROM
                  `cost`
              WHERE
                  `drink` = 'Tea'
          )
          )
      ) AS `total`,
      `Machine Name` as `machine`,
      `Time` as `time`
  FROM
      `EMPLOYEE`
  WHERE
      (
        `ID Code NO.` = '$eid' AND(`Date` BETWEEN '$from' AND '$to')
      )
  GROUP BY
      `Date`";

      $stmt = $this->connection->prepare($query);

      $stmt->execute();

      return $stmt;
  }
}