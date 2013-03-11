<?php

/**
 * User: Alexandru George Burghelea
 * Date: 02.03.2012
 * Time: 11:56 PM
 * For : PWeb 2013
 */

interface IScaffold
{
    /*
     * extrage intrarile din tabela cu conditia $field = $value; le ordoneaza corespunzator daca parametrii de
     * ordonare au fost trimisi; limiteaza rezultatele daca parametrii $limit si $show sunt setati
     * intoarce un array asociativ sau null daca nu a fost gasita nicio intrare
     */
    public function getRowsByField($field, $value, $orderby = '', $direction = 'ASC', $limit = '', $show = '');

    /*
     *  extrage intrarile din tabela cu conditiile $key => $value, unde $key => $value sunt elemente din $arr;
     * le ordoneaza corespunzator daca parametrii de ordonare au fost trimisi; limiteaza rezultatele daca
     * parametrii $limit si $show sunt setati
     * intoarce un array asociativ sau null daca nu a fost gasita nicio intrare
     */
    public function getRowsByArray($arr, $orderby = '', $direction = 'ASC', $limit = '', $show = '');

    /*
     * extrage intrarile din tabela pe baza query-ului trimis ca parametru
     * intoarce un array asociativ sau null daca nu a fost gasita nicio intrare
     */
    public function getCustomRows($query);

    /*
     * seteaza valorile fiecarui camp $key la $value, unde $key => $value sunt elemente din $arr, pentru
     * intrarile care au $field = $value
     */
    public function updateRows($arr, $field, $value);

    /*
     * insereaza in tabela o intrare cu campurile $key la valoarea $value, unde $key => $value sunt elemente
     * din $arr
     */
    public function insertRow($arr);

    /* executa query-ul primit ca parametru */
    public function customQuery($query);
}

?>