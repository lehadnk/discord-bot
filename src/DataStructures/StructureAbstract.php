<?php
/**
 * StructureAbstract.php
 * Creator: lehadnk
 * Date: 29/03/2017
 */

namespace DataStructures;


abstract class StructureAbstract
{
    public function __construct(\StdClass $data)
    {
        foreach ($data as $field => $value) {
            $this->{$field} = $value;
        }
    }
}