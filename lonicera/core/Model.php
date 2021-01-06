<?php

namespace lonicera\core;

class Model
{
    public function save()
    {
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        $keyArray = array_column($props, 'name');
        $keys = implode(',', $keyArray);
        $prepareKeys = implode(',', array_map(function ($key) {
            return ':' . $key;
        }, $keyArray));
        $sqlTemplate = "INSERT INTO " . $this->getTableNameByPO($reflect) . "({$keys}) VALUE ($prepareKeys)";
        $data = [];
        foreach ($props as $v) {
            $data[$v->name] = $reflect->getProperty($v->name)->getValue($this);
        }
        require_once _SYS_PATH . 'core/Db.php';
        $db = DB::getInstance($GLOBALS['_config']['db']);
        $ret = $db->execute($sqlTemplate, $data);
        return $ret;
    }

    public function deleteByPid()
    {

    }

    public function update()
    {

    }

    public function find()
    {

    }

    public function buildPrimaryWhere()
    {

    }

    public function getRealTableName($tableName, $prefix = '')
    {
        if (!empty($prefix)) {
            $realTableName = $prefix . "_{$tableName}";
        } elseif (isset($GLOBALS['_config']['db']['prefix']) && !empty($GLOBALS['_config']['db']['prefix'])) {
            $realTableName = $GLOBALS['_config']['db']['prefix'] . "_{$tableName}";
        } else {
            $realTableName = $tableName;
        }

        return $realTableName;
    }

    public function buildPO($tableName, $prefix = '')
    {
        $db = Db::getInstance($GLOBALS['_config']['db']);
        $ret = $db->query('SELECT * FROM `information_schema` . `COLUMNS` WHERE TABLE_NAME =: TABLENAME', array('TABLENAME' => $this->getRealTableName($tableName, $prefix)));
        $className = ucfirst($tableName);
        $file = _APP . 'model/' . $className . '.php';
        $classString = "<?php \r\nclass $className extends Model { \r\n>";
        foreach ($ret as $key => $value) {
            $classString .= 'public $' . "{$value['COLUMN_NAME']}";
            if (!empty($value['COLUMN_NAME'])) {
                $classString .= " // {$value['COLUMN_NAME']}";
            }
            $classString .= "\r\n";
        }
        $classString .= "}";
        file_put_contents($file, $classString);
    }

    public function getTableNameByPO($reflect)
    {
        return $this->getRealTableName(strtolower($reflect->getShortName()));
    }
}