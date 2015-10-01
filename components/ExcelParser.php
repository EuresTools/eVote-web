<?php

namespace app\components;

use Yii;
use \PHPExcel;
use \PHPExcel_IOFactory;
use yii\helpers\ArrayHelper;

class ExcelParser
{

    public static function parseMembers($filepath)
    {
        $file = file_get_contents($filepath);
        $workbook = PHPExcel_IOFactory::load($filepath);
        $worksheet = self::findSheet($workbook);

        $topFound = false;
        $prevName = null;
        $currRow = -1;
        $members = [];
        $member = null;
        foreach ($worksheet->getRowIterator() as $row) {
            $currRow++;
            if (!$topFound) {
                if (self::isTopRow($row)) {
                    $columns = self::getColumns($worksheet, $row);
                    $topFound = true;
                }
            } else {
                $name = trim(self::getCellValue($worksheet, $currRow, ArrayHelper::getValue($columns, 'name')));
                $index_name = strtolower($name);
                if ($index_name) {

                    if (!isset($members[$index_name])) {
                        // add member to members list
                        $member = [];
                        $member['row'] = $currRow + 1;
                        $member['rows'][] = $currRow + 1;
                        $member['name'] = trim($name);
                        $member['group'] = self::getCellValue($worksheet, $currRow, ArrayHelper::getValue($columns, 'group'));
                        $member['contacts'] = [];
                        $members[$index_name] = $member; // add member to members list
                    } else {
                        $members[$index_name]['rows'][] = $currRow + 1;
                    }

                    // go through the contacts of this line and add it to the member entry by the index name
                    $tups =[[$columns['contact1'], $columns['email1']], [$columns['contact2'], $columns['email2']]];
                    foreach ($tups as $tup) {
                        list($contact_col, $email_col) = $tup;
                        $emailValue = trim(self::getCellValue($worksheet, $currRow, $email_col));
                        if ($emailValue !== null && $emailValue !== '') {
                            $contact = [];
                            $contact['row'] = $currRow + 1;
                            $contact['email'] = $emailValue;

                            $nameValue = trim(self::getCellValue($worksheet, $currRow, $contact_col));
                            if ($nameValue !== null && $nameValue !== '') {
                                $contact['name'] = $nameValue;
                            }
                            $members[$index_name]['contacts'][] = $contact;
                        }
                    }
                }
                $prevName = $name;
            }
        }
        if (!$topFound) {
            return false;
        }
        //print_pre($columns, 'columns', false , false);
        //print_pre($topFound, 'topFound', false , false);
        //print_pre($members,'members',false, false, 100);
        return $members;
    }

    /*
    * Get the value of a cell indexed by row and column number starting from 0
    */
    private static function getCellValue($worksheet, $row, $column)
    {
        $row++;
        return $worksheet->getCellByColumnAndRow($column, $row)->getCalculatedValue();
    }


    /*
    * Delete all word-separating characters, make lowercase and write organization with z, not s.
    */
    private static function cleanString($string)
    {
        $string = strtolower($string);
        $separators = [' ', '_', '-'];
        $string = str_replace($separators, '', $string);
        $string = str_replace('organis', 'organiz', $string);
        return $string;
    }

    /* Find the relevant sheet and return it. */
    private static function findSheet($workbook)
    {
        $worksheet = $workbook->getActiveSheet();
        return $worksheet;
    }

    /* Returns the index of the header row. */
    private static function findTopRow($worksheet)
    {
        $expectedValues = ['organization', 'stakeholdergroup', 'contactperson1', 'contactperson2', 'emailaddress1', 'emailaddress2'];
        $currRow = -1;
        foreach ($worksheet->getRowIterator() as $row) {
            $currRow++;
            $values = [];
            $currCell = -1;

            foreach ($row->getCellIterator() as $cell) {
                $value = self::cleanString($cell->getCalculatedValue());
                $values[] = $value;
            }

            $isTop = true;
            foreach ($expectedValues as $ev) {
                if (!in_array($ev, $values)) {
                    $isTop = false;
                    break;
                }
            }
            if ($isTop) {
                return $currRow;
            }
        }
        return -1;
    }

    private static function isTopRow($row)
    {
        $expectedValues = [
            'organization',
            'stakeholdergroup',
            'contactperson1',
            'contactperson2',
            'emailaddress1',
            'emailaddress2'
        ];
        $values = [];
        foreach ($row->getCellIterator() as $cell) {
            $value = self::cleanString($cell->getCalculatedValue());
            $values[] = $value;
        }

        $isTop = true;
        foreach ($expectedValues as $ev) {
            if (!in_array($ev, $values)) {
                $isTop = false;
                break;
            }
        }
        return $isTop;
    }

    /* Returns the indices of the relevant columns. */
    private static function getColumns($worksheet, $topRow)
    {
        $columns = [
            'name' => 'organization',
            'group' => 'stakeholdergroup',
            'contact1' => 'contactperson1',
            'contact2' => 'contactperson2',
            'email1' => 'emailaddress1',
            'email2' => 'emailaddress2',
        ];
        //$columnNames = ['organization', 'stakeholdergroup', 'contactperson1', 'contactperson2', 'emailaddress1', 'emailaddress2'];
        $currCell = -1;
        foreach ($topRow->getCellIterator() as $cell) {
            $currCell++;
            $cellValue = $cell->getCalculatedValue();
            if (is_string($cellValue)) {

                $cellValue = self::cleanString($cellValue);
                // print_pre($cellValue,'cellValue',false);
                // print_pre($columns,'columns',false);

                $key = array_search($cellValue, $columns, true);
                // print_pre($key,'key',false);
                // print_pre($currCell,'currCell',false);
                // print_pre('-----','',false);
                if ($key !==false) {
                    $columns[$key] = $currCell;
                }
            }
        }
        return $columns;
    }
}
