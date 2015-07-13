<?php

namespace app\components;

use Yii;
use \PHPExcel;
use \PHPExcel_IOFactory;
use yii\helpers\ArrayHelper;

class ExcelParser {

    public static function parseMembers($filepath) {
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
            if(!$topFound) {
                if(self::isTopRow($row)) {
                    $columns = self::getColumns($worksheet, $row);
                    $topFound = true;
                }
            } else {
                $name = self::getCellValue($worksheet, $currRow, ArrayHelper::getValue($columns, 'name'));
                if (trim(strtolower($name)) !== trim(strtolower($prevName))) {
                    if($member !== null) {
                        $members[] = $member;
                    }
                    $member = [];
                    $member['row'] = $currRow + 1;
                    $member['name'] = $name;
                    $member['group'] = self::getCellValue($worksheet, $currRow, ArrayHelper::getValue($columns, 'group'));
                    $member['contacts'] = [];

                    $tups =[[$columns['contact1'], $columns['email1']], [$columns['contact2'], $columns['email2']]];
                    foreach ($tups as $tup) {
                        list($contact_col, $email_col) = $tup;
                        $emailValue = trim(self::getCellValue($worksheet, $currRow, $email_col));
                        if($emailValue !== null && $emailValue !== '') {
                            $contact = [];
                            $contact['row'] = $currRow + 1;
                            $contact['email'] = $emailValue;

                            $nameValue = self::getCellValue($worksheet, $currRow, $contact_col);
                            if($nameValue !== null && $nameValue !== '') {
                                $contact['name'] = $nameValue;
                            }
                            $member['contacts'][] = $contact;
                        }
                    }
                }
                $prevName = $name;
            }
        }
        if (!$topFound) {
            return False;
        }
        return $members;
    }


    /* Get the value of a cell indexed by row and column number starting from 0 
        * */
    private static function getCellValue($worksheet, $row, $column) {
        $row++;
        return $worksheet->getCellByColumnAndRow($column, $row)->getCalculatedValue();
    }


    /* Delete all word-separating characters, make lowercase and write organization with z, not s. 
     * */
    private static function cleanString($string) {
        $string = strtolower($string);
        $separators = [' ', '_', '-'];
        $string = str_replace($separators, '', $string);
        $string = str_replace('organis', 'organiz', $string);
        return $string;
    }

    /* Find the relevant sheet and return it. */
    private static function findSheet($workbook) {
        $worksheet = $workbook->getActiveSheet();
        return $worksheet;
    }

    /* Returns the index of the header row. */
    private static function findTopRow($worksheet) {
        $expectedValues = ['organization', 'stakeholdergroup', 'contactperson1', 'contactperson2', 'emailaddress1', 'emailaddress2'];
        $currRow = -1;
        foreach ($worksheet->getRowIterator() as $row) {
            $currRow++;
            $values = [];
            $currCell = -1;

            foreach($row->getCellIterator() as $cell) {
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

    private static function isTopRow($row) {
        $expectedValues = [
            'organization',
            'stakeholdergroup',
            'contactperson1',
            'contactperson2',
            'emailaddress1',
            'emailaddress2'
        ];
        $values = [];
        foreach($row->getCellIterator() as $cell) {
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
    private static function getColumns($worksheet, $topRow) {
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
            if(is_string($cellValue)) {
                $cellValue = self::cleanString($cellValue);
                if($key = array_search($cellValue, $columns)) {
                    $columns[$key] = $currCell;
                }
            }
        }
        return $columns;
    }
}
