<?php
/**
 * @Function: Lớp xử lý các hàm thực hiện Query Builder
 * @Author: trinh.kethanh@gmail.com
 * @Date: 19/03/2015
 * @System: Video 2.0
 */

namespace cms\components;

use Yii;

class QueryBuilder {
    /**
     * Generates a batch INSERT SQL statement whith ON DUPLICATE KEY condition.
     * For example,
     *
     * ~~~
     * $this->batchInsertDuplicate(['id' => ['1', '2']], ['name', 'age'], [
     *     ['Tom', 30],
     *     ['Jane', 20],
     *     ['Linda', 25],
     * ], ['name', 'age'])->execute();
     * ~~~
     *
     * Note that the values in each row must match the corresponding column names.
     *
     * @param string $table the table that rows will be inserted or updated into.
     * @param array $columns the column names.
     * @param array $rows the rows to be batch inserted or updated into the table.
     * @param array $duplicates column names to be updated ON DUPLICATE KEY.
     * @return string the batch INSERT ON DUPLICATE KEY SQL statement.
     */
    public static function batchInsertDuplicate($table, $columns, $rows, $duplicates = []) {
        $db = Yii::$app->db;
        if (($tableSchema = $db->getTableSchema($table)) !== null) {
            $columnSchemas = $tableSchema->columns;
        } else {
            $columnSchemas = [];
        }
        $sql = $db->getQueryBuilder()->batchInsert($table, $columns, $rows);
        if (!empty($duplicates)) {
            $columnDuplicates = [];
            foreach ($duplicates as $i => $column) {
                if (isset($columnSchemas[$column])) {
                    $column = $db->quoteColumnName($column);
                    $columnDuplicates[] = $column . ' = VALUES(' . $column . ')';
                }
            }
            if (!empty($columnDuplicates)) {
                $sql .= ' ON DUPLICATE KEY UPDATE ' . implode(',', $columnDuplicates);
            }
        }
        return $db->createCommand()->setSql($sql)->execute();
    }
}